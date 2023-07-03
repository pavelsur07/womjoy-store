<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Store\Infrastructure\Service\Cart\CartService;
use App\Store\Infrastructure\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route(path: '/cart/checkout/', name: 'store.checkout')]
    public function checkout(Response $response, CartService $cartService, OrderService $orderService): Response
    {
        $customerId = null;
        $user = $this->getUser();
        $customerId = $user?->getId();

        $cart = $cartService->getCurrentCart($customerId);
        $order = $orderService->checkout();
        return $this->render('store/cart/checkout.html.twig');
    }
}
