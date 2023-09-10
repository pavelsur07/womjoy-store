<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Cart;

use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public const PER_PAGE = 25;

    #[Route(path: '/admin/cars', name: 'store.cart.admin.index')]
    public function index(Request $request, CartRepository $carts): Response
    {
        return $this->render(
            'store/admin/cart/index.html.twig',
            [
                'pagination' => $carts->getAll(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
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
