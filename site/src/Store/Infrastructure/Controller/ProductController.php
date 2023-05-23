<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/product/{slug}', name: 'store.product.show')]
    public function show(string $slug, Product $product, ProductRepository $products): Response
    {
        return $this->render(
            'store/product/show.html.twig',
            [
                'product' => $product,
            ]
        );
    }
}
