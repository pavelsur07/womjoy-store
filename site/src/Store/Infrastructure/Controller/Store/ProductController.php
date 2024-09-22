<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\JsonLd\Breadcrumb\JsonLdBreadcrumb;
use App\Common\Infrastructure\JsonLd\JsonLdGenerator;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    #[Route(path: '/products/{slug}', name: 'store.product.show')]
    public function show(string $slug, Product $product, Request $request, ProductRepository $products): Response
    {
        if ($product->getSeoMetadata()->getSeoTitle() !== null) {
            $this->setTitle($product->getSeoMetadata()->getSeoTitle());
        }

        if ($product->getSeoMetadata()->getSeoDescription()) {
            $this->setDescription($product->getSeoMetadata()->getSeoDescription());
        }

        $this->metaData['jsonLdProduct'] = JsonLdGenerator::generate($this->generateJsonLdProduct($product));

        $breadcrumbs = $this->breadcrumbsCategoryGenerate($product->getMainCategory());
        $breadcrumbs[] =
            [
                'name' =>$product->getName(),
                'slug' => $product->getSlug(),
                'href' => $this->generateUrl('store.product.show', ['slug' => $product->getSlug()]),
            ];

        $this->metaData['jsonLdBreadcrumb'] = JsonLdGenerator::generate(
            JsonLdBreadcrumb::generate(
                categories: $breadcrumbs,
                baseUrl: $this->metaData['base_url']
            )
        );

        return $this->render(
            "{$this->template}/store/product/show.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'breadcrumbs'=> $this->breadcrumbsCategoryGenerate($product->getMainCategory()),
                'product' => $product,
                'referer' => $request->headers->get('referer'),
                'jsProduct' => [
                    'id' => (string)$product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice()->getListPrice(),
                    'brand' => $product->getBrandName(),
                    'category' => 'Одежда/Мужская одежда/Футболки', // TODO Добавить вывод хлебныхкрошек
                    'variant' => $product->getColor(),
                    'list' => 'Результаты поиска',
                    'position' => 1,
                ],
            ]
        );
    }
}
