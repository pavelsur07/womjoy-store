<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\OrderItem;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Exception\StoreOrderException;
use App\Store\Infrastructure\Service\Order\OrderService;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

#[Route(path: '/cart/checkout/payment/stripe', name: 'store.checkout.payment.stripe')]
class StripeController extends AbstractController
{
    public function __construct(
        private readonly string $stripeApiKey,
        private readonly OrderService $orderService,
        private readonly Flusher $flusher,
    ) {
        Stripe::setApiKey($this->stripeApiKey);
    }

    #[Route('/{orderId}', name: '.purchase')]
    public function purchase(string $orderId): Response
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        $lineItems = [];

        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bgn',
                    // @TODO переключить на получение кода валюты.
                    //                    'currency' => $item->getPrice()->getCurrency(),
                    'product_data' => [
                        'name' => $item->getProductData()->getName(),
                    ],
                    'unit_amount' => $item->getPrice()->getSalePrice(),
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl(
                'store.checkout.payment.stripe.success',
                ['orderId' => $orderId],
                UrlGenerator::ABSOLUTE_URL
            ),
            'cancel_url' => $this->generateUrl(
                'store.checkout.payment.stripe.fail',
                ['orderId' => $orderId],
                UrlGenerator::ABSOLUTE_URL
            ),
        ]);

        $order->getPayment()->setTransactionId($session->id);
        $order->getPayment()->setTransaction(
            $session->toArray()
        );

        $this->flusher->flush();

        return $this->redirect($session->url, 303);
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
    public function fail(string $orderId): Response
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        try {
            $order->cancel('Payment fail.');
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

    #[Route('/{orderId}/transaction', name: '.transaction')]
    public function transaction(string $orderId): void
    {
        $order = $this->orderService->get(
            new OrderId($orderId)
        );

        if ($order->getPayment()->getTransactionId()) {
            $session = \Stripe\Checkout\Session::retrieve(
                $order->getPayment()->getTransactionId()
            );

            dump($session);
            exit;
        }

        dump($order);
        exit;
    }
}
