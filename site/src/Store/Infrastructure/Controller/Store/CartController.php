<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Infrastructure\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart', name: 'store.cart')]
class CartController extends BaseController
{
    #[Route(path: '/', name: '.index')]
    public function cart(Request $request, CartService $service): Response
    {
        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId();

        return $this->render(
            'store/cart/cart.html.twig',
            [
                'user' => $userId,
                'cart' => $service->getCurrentCart(customerId: $userId),
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }

    #[Route(path: '/add', name: '.add')]
    public function add(): Response
    {
        return $this->json([]);
    }

    #[Route(path: '/quantity', name: '.quantity')]
    public function quantity(): Response
    {
        return $this->json([]);
    }

    #[Route(path: '/remove', name: '.remove')]
    public function remove(): Response
    {
        return $this->json([]);
    }

    public function clear(): Response
    {
        return $this->json([]);
    }
}
