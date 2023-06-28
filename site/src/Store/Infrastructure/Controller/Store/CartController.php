<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart', name: 'store.cart')]
class CartController extends BaseController
{
    #[Route(path: '/', name: '.index')]
    public function cart(): Response
    {
        return $this->render(
            'store/cart/cart.html.twig',
            [
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
