<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route(path: '/admin/cars', name: 'store.cart.admin.index')]
    public function index(CartRepository $carts): Response
    {
        return $this->render(
            'store/admin/cart/index.html.twig',
            [
                'carts' => $carts->getAll(),
            ]
        );
    }

    #[Route(path: '/admin/card/{id}/show', name: 'store.cart.admin.show')]
    public function show(int $id, Cart $cart): Response
    {
        return $this->render(
            'store/admin/cart/show.html.twig',
            [
                'cart' => $cart,
            ]
        );
    }
}
