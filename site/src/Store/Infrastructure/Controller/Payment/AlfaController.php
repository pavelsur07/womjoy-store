<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\OrderItem;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Exception\StoreOrderException;
use App\Store\Infrastructure\Service\Order\OrderService;
use App\Store\Infrastructure\Service\Payment\AlfaAcquiringClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Voronkovich\SberbankAcquiring\Exception\SberbankAcquiringException;
use Voronkovich\SberbankAcquiring\OrderStatus;

#[Route(path: '/cart/checkout/payment/alfa', name: 'store.checkout.payment.alfa')]
class AlfaController extends AbstractController
{
    public function __construct(
        private readonly AlfaAcquiringClient $acquiringClient,
        private readonly OrderService $orderService,
        private readonly Flusher $flusher,
    ) {}

    #[Route('/{orderId}', name: '.purchase')]
    public function purchase(string $orderId): Response
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        $orderItems = [];

        /**
         * @var OrderItem $orderItem
         */
        foreach ($order->getItems() as $index => $orderItem) {
            $price = $orderItem->getPrice()->getSalePrice();
            $quantity = $orderItem->getQuantity();

            $orderItems[] = [
                'positionId' => $index,
                'name' => $orderItem->getProductData()->getName(),
                'quantity' => [
                    'value' => $quantity,
                    'measure' => 0,
                ],
                'itemCode' => $orderItem->getId(),
                'itemPrice' => $price * 100,
                'itemAmount' => ($price * $quantity) * 100,
            ];
        }

        try {
            $alfaOrder = $this->acquiringClient->registerOrder(
                // Уникальный номер заказа
                orderId: $order->getOrderNumber()->value(),

                // Конвертируем в копейки
                amount: ($order->getTotalCost() * 100),

                // Генерируем урл возврата
                returnUrl: $this->generateUrl(
                    route: 'store.checkout.payment.alfa.result',
                    parameters: [
                        'orderId' => $orderId,
                    ],
                    referenceType: UrlGeneratorInterface::ABSOLUTE_URL
                ),
                data: [
                    'orderBundle' => [
                        'cartItems' => [
                            'items' => $orderItems,
                        ],
                    ],
                ],
            );
        } catch (SberbankAcquiringException $exception) {
            return $this->redirectToRoute(
                'store.checkout.payment.alfa.fail',
                ['orderId' => $orderId, 'reason' => $exception->getMessage()]
            );
        }

        $order->getPayment()->setTransactionId($alfaOrder['orderId']);
        $order->getPayment()->setTransaction($alfaOrder);

        $this->flusher->flush();

        return $this->redirect($alfaOrder['formUrl'], 303);
    }

    #[Route('/{orderId}/result', name: '.result')]
    public function result(string $orderId): Response
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        $alfaOrderStatus = $this->acquiringClient->getOrderStatus(
            $order->getPayment()->getTransactionId()
        );

        $redirectRoute = match ($alfaOrderStatus['orderStatus']) {
            OrderStatus::CREATED, OrderStatus::APPROVED, OrderStatus::DEPOSITED => 'store.checkout.payment.alfa.success',
            OrderStatus::REVERSED, OrderStatus::DECLINED, OrderStatus::REFUNDED => 'store.checkout.payment.alfa.fail',
        };

        $order->getPayment()->setTransaction($alfaOrderStatus);

        $this->flusher->flush();

        return $this->redirectToRoute($redirectRoute, ['orderId' => $orderId]);
    }

    #[Route('/{orderId}/success', name: '.success')]
    public function success(string $orderId): Response
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
    public function fail(Request $request, string $orderId): Response
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
