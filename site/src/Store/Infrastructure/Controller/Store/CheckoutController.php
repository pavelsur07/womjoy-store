<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Application\SendMail\Order\OrderStatusSendMailCommand;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Infrastructure\Service\Cart\CartService;
use App\Store\Infrastructure\Service\Order\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends BaseController
{
    #[Route(path: '/cart/checkout/', name: 'store.checkout')]
    public function checkout(CartService $cartService): Response
    {
        $cart = $cartService->getCurrentCart(
            $this->getUser()?->getId()
        );

        if (!$cart->getItems()->count()) {
            return $this->redirectToRoute('store.cart.index');
        }

        return $this->render("{$this->template}/store/cart/checkout.html.twig", [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
        ]);
    }

    #[Route(path: '/cart/checkout/{orderId}/finish/', name: 'store.checkout.finish')]
    public function finish(OrderService $orderService, CartService $cartService, string $orderId, MessageBusInterface $bus): Response
    {
        $cartService->clear(
            $this->getUser()?->getId()
        );

        $order = $orderService->get(
            new OrderId($orderId)
        );

        $bus->dispatch(new OrderStatusSendMailCommand(orderUuid: $order->getId()->value()));

        return $this->render("{$this->template}/store/cart/checkout-finish.html.twig", [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
            'order' => $order,
        ]);
    }

    #[Route(path: '/cart/checkout/{orderId}/fail/', name: 'store.checkout.fail')]
    public function fail(OrderService $orderService, string $orderId): Response
    {
        $order = $orderService->get(
            new OrderId($orderId)
        );

        return $this->render("{$this->template}/store/cart/checkout-fail.html.twig", [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
            'order' => $order,
        ]);
    }
}
