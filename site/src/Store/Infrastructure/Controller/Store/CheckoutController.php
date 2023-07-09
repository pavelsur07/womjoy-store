<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Infrastructure\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
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

        return $this->render('store/cart/checkout.html.twig', [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
        ]);
    }

    #[Route(path: '/cart/checkout/finish/', name: 'store.checkout.finish')]
    public function finish(CartService $cartService): Response
    {
        $cartService->clear(
            $this->getUser()?->getId()
        );

        return $this->render('store/cart/checkout-finish.html.twig', [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
        ]);
    }
}
