<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/metrika', name: 'store.metrika.api')]
class MetricaApiController extends AbstractController
{
    // Нужные события
    // impression — просмотр списка товаров;
    // detail — просмотр товара;
    // add — добавление товара в корзину;
    // remove — удаление товара из корзины;
    // purchase — покупка;

    #[Route(path: '/get', name: '.get', methods: ['POST'], format: 'json')]
    public function get(Request $request, ProductRepository $productRepository): Response
    {
        $action = $request->getPayload()->get('action');
        $orderId = $request->getPayload()->get('orderId');
        $productsIds = $request->getPayload()->all('products');

        $makeCategoryName = function (Category $category) use (&$makeCategoryName) {
            $categoryName = [
                $category->getName()
            ];

            if ($category->getParent()) {
                return array_merge($categoryName, $makeCategoryName($category->getParent()));
            }

            return $categoryName;
        };

        $results = [];
        foreach ($productsIds as $position => $productsId) {
            $product = $productRepository->get($productsId);

            // ищим харакетристику бренд
            $brands = $product->getAttributes()->filter(function ($value) {
                return $value->getAttribute()->isBrand();
            });

            // получаем бренд
            $brand = !$brands->isEmpty() ? $brands->first()->getVariant()->getName() : null;

            // ищим харакетристику цвета
            $colors = $product->getAttributes()->filter(function ($value) {
                return $value->getAttribute()->isColor();
            });

            // получаем цвет
            $color = !$colors->isEmpty() ? $colors->first()->getVariant()->getName() : null;


            // собираем список категорий
            $categoryName = $product->getMainCategory() ? array_reverse(
                $makeCategoryName($product->getMainCategory())
            ) : [];

            // конвертим в строку
            $categoryName = implode(' / ', $categoryName);


            $results[] = [
                "id" => $product->getArticle(),
                "name" => $product->getName(),
                "price" => $product->getPrice()->getListPrice(),
                "brand" => $brand,
                "category" => $categoryName,
                "variant" => $color,
                "position" => $position + 1,
            ];
        }

        $data = match ($action) {
            'impression', 'remove', 'add', 'detail' => [
                'currencyCode' => 'RUB',
                $action => ['products' => $results]
            ],
             'purchase'  => [
                'currencyCode' => 'RUB',
                $action => ['actionField' => ['id' => $orderId], 'products' => $results]
            ],
        };

        return $this->json($data);
    }
}
