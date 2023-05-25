<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    #[Route(path: '/product/{slug}', name: 'store.product.show')]
    public function show(string $slug, Product $product, ProductRepository $products): Response
    {
        return $this->render(
            'store/product/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'breadcrumbs'=> $this->breadcrumbsCategoryGenerate($product->getMainCategory()),
                'product' => $product,
            ]
        );
    }
}
