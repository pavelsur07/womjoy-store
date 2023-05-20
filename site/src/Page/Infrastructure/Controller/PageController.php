<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Controller;

use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/pages', name: 'store.page')]
class PageController extends AbstractController
{
    public const PER_PAGE= 15;

    #[Route(path: '/pay-and-delivery', name: '.pay_delivery')]
    public function payAndDelivery(Request $request, ProductRepository $products): Response
    {
        return $this->render(
            'store/page/pay_delivery.html.twig',
            []
        );
    }
}