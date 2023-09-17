<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Attribute\Variant;
use App\Store\Domain\Entity\Category\AttributeAssignment;
use App\Store\Domain\Entity\Category\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category/filters', name: 'store.admin.category.filter')]
class CategoryFilterController extends AbstractController
{
    #[Route(path: '/{id}/update', name: '.update')]
    public function update(int $id, Category $category, Request $request, Flusher $flusher): Response
    {
        $attributes = [];
        $result = [];
        /** @var Category $child */
        foreach ($category->getChildren() as $child) {
            /** @var AttributeAssignment $attribute */
            foreach ($child->getAttributes() as $attribute) {
                /** @var Variant $variant */
                foreach ($attribute->getAttribute()->getVariants() as $variant) {
                    $result[] = [
                        'attribute_id' => $attribute->getAttribute()->getId(),
                        'attribute_name' => $attribute->getAttribute()->getName(),
                        'value_id' => $variant->getId(),
                        'value' => $variant->getName(),
                    ];
                }
                $attributes = array_merge($attributes, $result);
            }
        }

        $filters = [];
        foreach ($attributes as $item) {
            $key = $item['attribute_id'];
            if (!isset($filters[$key])) {
                $filters[$key] = [
                    'attribute_id' => $item['attribute_id'],
                    'attribute_name' => $item['attribute_name'],
                    'items' => [],
                ];
            }
            $valueKey = $item['value_id'];
            if (!isset($filters[$key]['items'][$valueKey])) {
                $filters[$key]['items'][$valueKey] = [
                    'value_id' => $item['value_id'],
                    'value' => $item['value'],
                ];
            }
        }

        $category->updateFilters($filters);
        $flusher->flush();

        return $this->redirectToRoute('store.admin.category.edit', ['id' => $id]);
    }
}
