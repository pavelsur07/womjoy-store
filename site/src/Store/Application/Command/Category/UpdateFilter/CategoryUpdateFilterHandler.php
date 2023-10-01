<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Category\UpdateFilter;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Attribute\Variant;
use App\Store\Domain\Entity\Category\AttributeAssignment;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class CategoryUpdateFilterHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categories,
        private Flusher $flusher,
    ) {}

    #[AsMessageHandler]
    public function __invoke(CategoryUpdateFilterCommand $command): void
    {
        $category = $this->categories->get($command->getCategoryId());

        $attributes = [];
        $result = [];

        // Добавляем характеристики из дочерних категорий
        /** @var Category $child */
        foreach ($category->getChildren() as $child) {
            /** @var AttributeAssignment $attribute */
            foreach ($child->getAttributes() as $attribute) {
                if ($attribute->getAttribute()->isVisibleFilter()) {
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
        }

        // Добавляем характеристики из текущей категории
        /** @var AttributeAssignment $attribute */
        foreach ($category->getAttributes() as $attribute) {
            if ($attribute->getAttribute()->isVisibleFilter()) {
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
        $this->flusher->flush();
    }
}
