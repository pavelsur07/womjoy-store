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
        return $this->render('default/store/cart/cart.html.twig', [
            'menu' => $this->menu,
            'metaData' => $this->metaData,
        ]);
    }
}
