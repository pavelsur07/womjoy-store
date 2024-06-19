<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Cart;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Infrastructure\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public const PER_PAGE = 25;

    #[Route(path: '/admin/cards', name: 'store.cart.admin.index')]
    public function index(Request $request, CartRepository $carts): Response
    {
        return $this->render(
            'admin/store/cart/index.html.twig',
            [
                'pagination' => $carts->getAllCustomerEmailNotNull(
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
            'admin/store/cart/show.html.twig',
            [
                'cart' => $cart,
            ]
        );
    }

    #[Route(path: '/admin/cards/old', name: 'store.cart.admin.index_old')]
    public function oldCart(Request $request, CartRepository $carts): Response
    {
        return $this->render(
            'admin/store/cart/index_old.html.twig',
            [
                'pagination' => $carts->getOldCarts(),
            ]
        );
    }

    #[Route(path: '/admin/cards/old/remove', name: 'store.cart.admin.index_old.remove')]
    public function removeOldCarts(CartRepository $carts, Flusher $flusher): Response
    {
        $oldCarts = $carts->getOldCarts();

        foreach ($oldCarts as $cart) {
            $carts->remove($cart);
        }

        $flusher->flush();

        return $this->redirectToRoute('store.cart.admin.index_old');
    }
}
