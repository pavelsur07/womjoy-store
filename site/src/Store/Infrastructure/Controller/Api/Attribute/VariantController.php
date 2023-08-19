<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api\Attribute;

use App\Store\Domain\Entity\Attribute\Variant;
use App\Store\Infrastructure\Repository\AttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/attribute', name: 'store.admin.api.attribute.variant')]
class VariantController extends AbstractController
{
    #[Route(path: '/{id}/variants/', name: '.get')]
    public function get(int $id, Request $request, AttributeRepository $attributes): Response
    {
        $attribute = $attributes->get($id);
        $result = [];

        /** @var Variant $variant */
        foreach ($attribute->getVariants() as $variant) {
            $result[] = [
                'id' => $variant->getId(),
                'value' => $variant->getName(),
            ];
        }
        return $this->json(
            [
                'variants' => $result,
            ]
        );
    }
}
