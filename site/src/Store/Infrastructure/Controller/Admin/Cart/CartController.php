<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Cart;

use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route(path: '/admin/cars', name: 'store.cart.admin.index')]
    public function index(CartRepository $carts)
    {
        return $this->render(
            'store/admin/cart/index.html.twig',
            [
                'carts' => $carts->getAll(),
            ]
        );
    }
}
