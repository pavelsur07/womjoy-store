<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\OrderItem;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Exception\StoreOrderException;
use App\Store\Infrastructure\Service\Order\OrderService;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\AuthorizeException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\BadRequestException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\ConflictException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart\CartTotal;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart\ItemQuantity;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart\RenderedCart;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart\RenderedCartItem;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\MerchantRedirectUrls;
use App\Store\Infrastructure\Service\Payment\YandexPay\Request\CreateOrderRequest;
use App\Store\Infrastructure\Service\Payment\YandexPay\YandexPay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/cart/checkout/payment/yandex_split', name: 'store.checkout.payment.yandex_split')]
class YandexSplitController extends AbstractController
{
    public function __construct(
        private readonly YandexPay $yandexPay,
        private readonly OrderService $orderService,
        private readonly Flusher $flusher,
    ) {}

    #[Route('/{orderId}', name: '.purchase')]
    public function purchase(string $orderId): Response
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        $items = [];

        /**
         * @var OrderItem $item
         */
        foreach ($order->getItems() as $item) {
            $item = new RenderedCartItem(
                productId: (string)$item->getProductVariant()->getId(),
                quantity: new ItemQuantity($item->getQuantity()),
                title: $item->getProductData()->getName(),
                total: (float)$item->getPrice()->getSalePrice()
            );

            $items[] = $item;
        }

        $cart = new RenderedCart(
            $orderId,
            $items,
            new CartTotal($order->getTotalCost())
        );

        $redirectUrls = new MerchantRedirectUrls();
        $redirectUrls->setOnSuccess(
            $this->generateUrl('store.checkout.payment.yandex_split.success', ['orderId' => $orderId], UrlGeneratorInterface::ABSOLUTE_URL)
        );
        $redirectUrls->setOnAbort(
            $this->generateUrl('store.checkout.payment.yandex_split.fail', ['orderId' => $orderId], UrlGeneratorInterface::ABSOLUTE_URL)
        );
        $redirectUrls->setOnError(
            $this->generateUrl('store.checkout.payment.yandex_split.fail', ['orderId' => $orderId], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        $transactionId = OrderId::generate()->value();

        try {
            $result = $this->yandexPay->createOrder(
                new CreateOrderRequest($transactionId, $cart, $redirectUrls)
            );
        } catch (AuthorizeException|BadRequestException|ConflictException $e) {
            return $this->redirectToRoute(
                'store.checkout.payment.yandex_split.fail',
                ['orderId' => $orderId, 'reason' => $e->getMessage()]
            );
        }

        $order->getPayment()->setTransactionId($transactionId);
        $order->getPayment()->setTransaction($result->toArray());

        $this->flusher->flush();

        return $this->redirect($result->getPaymentUrl(), 303);
    }

    #[Route('/{orderId}/success', name: '.success')]
    public function success(string $orderId): RedirectResponse
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        $order->pay();
        $order->getPayment()->setStatusSucceeded();

        $this->flusher->flush();

        return $this->redirectToRoute(
            'store.checkout.finish',
            ['orderId' => $orderId]
        );
    }

    #[Route('/{orderId}/fail', name: '.fail')]
    public function fail(Request $request, string $orderId): RedirectResponse
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        // Get payment fail reason or default
        $reason = $request->query->get('reason', 'Payment fail.');

        try {
            $order->cancel($reason);
            $order->getPayment()->setStatusCancelled();

            $this->flusher->flush();
        } catch (StoreOrderException $exception) {
            // @todo если нужен лог то он будет тут
        }

        return $this->redirectToRoute(
            'store.checkout.fail',
            ['orderId' => $orderId]
        );
    }
}
