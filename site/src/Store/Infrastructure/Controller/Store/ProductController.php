<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    #[Route(path: '/products/{slug}', name: 'store.product.show')]
    public function show(string $slug, Product $product, ProductRepository $products): Response
    {
        if ($product->getSeoMetadata()->getSeoTitle() !== null) {
            $this->setTitle($product->getSeoMetadata()->getSeoTitle());
        }

        if ($product->getSeoMetadata()->getSeoDescription()) {
            $this->setDescription($product->getSeoMetadata()->getSeoDescription());
        }

        return $this->render(
            "{$this->template}/store/product/show.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'breadcrumbs'=> $this->breadcrumbsCategoryGenerate($product->getMainCategory()),
                'product' => $product,
                'jsProduct' => [
                    'id' => (string)$product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice()->getListPrice(),
                    'brand' => 'Яндекс / Яndex',
                    'category' => 'Одежда/Мужская одежда/Футболки',
                    'variant' => 'Красный цвет',
                    'list' => 'Результаты поиска',
                    'position' => 1,
                ],
            ]
        );
    }
}
