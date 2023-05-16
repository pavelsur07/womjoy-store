<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/product/{id}', name: 'store.product.show', requirements: ['_locale' => 'en|ru|bg'])]
    public function show(int $id, ProductRepository $products): Response
    {
        return $this->render(
            'store/product/show.html.twig',
            [
                'product' => $products->get($id),
            ]
        );
    }
}
