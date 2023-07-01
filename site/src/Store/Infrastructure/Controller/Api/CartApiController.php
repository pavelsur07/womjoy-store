<?php

namespace App\Store\Infrastructure\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/cart',name: 'store.cart.api')]
class CartApiController extends AbstractController
{
    #[Route(path: '/',name: '.get',methods: ['GET'])]
    public function get(Request $request): Response
    {
        return $this->json(
            [
                'customer_id' => null,
                'cost' => null,
                'amount' => 10,
                'items' => [],
            ]
        );
    }
}