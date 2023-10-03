<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Category\AttributeAssignment;
use App\Store\Infrastructure\Repository\AttributeRepository;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/product', name: 'store.admin.api.product.attribute')]
class AttributeController extends AbstractController
{
    #[Route(path: '/{id}/attribute', name: '.get')]
    public function index(int $id, ProductRepository $products): Response
    {
        $product = $products->get($id);
        $result = [];

        /** @var AttributeAssignment $item */
        foreach ($product->getMainCategory()->getAttributes() as $item) {
            $result[] = [
                'attribute_id' => $item->getAttribute()->getId(),
                'name' => $item->getAttribute()->getName(),
                'type' => $item->getAttribute()->getType(),
                'values' => [],
            ];
        }

        return $this->json(
            [
                'id' => $id,
                'name' => 'Name',
                'items' => $result,
            ]
        );
    }

    #[Route(path: '/{id}/attribute/edit', name: '.edit')]
    public function edit(
        int $id,
        Request $request,
        ProductRepository $products,
        AttributeRepository $attributes,
        Flusher $flusher
    ): Response {
        $body = $request->getContent();

        $product = $products->get($id);
        $product->clearAttributes();

        $data = json_decode($body, true);

        foreach ($data['attributes'] as $item) {
            $attribute = $attributes->get((int)$item['attribute_id']);
            $variant = $attribute->getVariant((int)$item['variant_id']);

            $product->assignAttribute(attribute: $attribute, variant: $variant);
        }

        $products->flush();

        $result = [];

        /** @var AttributeAssignment $item */
        foreach ($product->getMainCategory()->getAttributes() as $item) {
            $result[] = [
                'attribute_id' => $item->getAttribute()->getId(),
                'name' => $item->getAttribute()->getName(),
                'type' => $item->getAttribute()->getType(),
                'values' => [],
            ];
        }

        return $this->json([
            'message' => 'success',
            'data' => $data,
            'id' => $id,
            'name' => 'Name',
            'items' => $result,
        ]);
    }
}
