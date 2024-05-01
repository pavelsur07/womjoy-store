<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api\Product;

use App\Store\Domain\Entity\Product\Variant;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/product', name: 'store.admin.api.product')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $products,
    ) {}

    #[Route(path: '/{id}/get', name: '.get')]
    public function getProduct(int $id, Request $request): Response
    {
        $product = $this->products->get($id);

        $variants = [];
        /** @var Variant $variant */
        foreach ($product->getVariants() as $variant) {
            $variants[] = [
                'id' => $variant->getId(),
                'value' => $variant->getValue(),
                'quantity' => $variant->getQuantity(),
            ];
        }

        return $this->json([
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'variants' => $variants,
            ],
        ]);
    }

    #[Route(path: '/{id}/subscribe', name: '.subscribe')]
    public function subscribe(int $id, Request $request): Response
    {
        return $this->json(
            [
                'success' => true,
            ]
        );
    }
}
