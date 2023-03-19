<?php

declare(strict_types=1);

namespace App\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route(path: '/cart/', name: 'store.cart')]
    public function cart(): Response
    {
        return $this->render('store/cart/cart.html.twig');
    }
}
