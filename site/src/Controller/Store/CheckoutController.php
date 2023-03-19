<?php

declare(strict_types=1);

namespace App\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route(path: '/checkout/', name: 'store.checkout')]
    public function checkout(): Response
    {
        return $this->render('store/cart/checkout.html.twig');
    }
}
