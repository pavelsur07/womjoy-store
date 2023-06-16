<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends BaseController
{
    #[Route(path: '/cart/', name: 'store.cart')]
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
}
