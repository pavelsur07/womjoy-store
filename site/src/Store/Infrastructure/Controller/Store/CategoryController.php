<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Attribute\Variant;
use App\Store\Domain\Entity\Category\AttributeAssignment;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    public const PER_PAGE = 15;

    public const SORTING_RULE_POPULARITY = 'popularity';
    public const SORTING_RULE_PRICE_ASC = 'price-asc';
    public const SORTING_RULE_PRICE_DESC = 'price-desc';

    public const SORTING_RULES = [
        self::SORTING_RULE_POPULARITY => 'По популярности',
        self::SORTING_RULE_PRICE_ASC => 'По возрастанию цены',
        self::SORTING_RULE_PRICE_DESC => 'По убыванию цены',
    ];

    #[Route(path: '/collections/{slug}', name: 'store.category.show')]
    public function index(string $slug, Category $category, Request $request, ProductRepository $products): Response
    {
        if ($category->getSeoMetadata()->getSeoTitle() !== null) {
            $this->setTitle($category->getSeoMetadata()->getSeoTitle());
        }
        if ($category->getSeoMetadata()->getSeoDescription()) {
            $this->setDescription($category->getSeoMetadata()->getSeoDescription());
        }

        // Получение спсика установленных фильтров
        $filterValues = $request->query->all('filter');

        $filterSettingIds = [];
        foreach ($filterValues as $filters) {
            foreach ($filters as $filterValue) {
                $filterSettingIds[] = $filterValue;
            }
        }

        $filterSettingIds = implode('_', $filterSettingIds);
        //
        //        // Получение спсика установленных фильтров
        //        $filterSettingIds = $request->query->get('filter_ids');

        // Получаем правило сортировки
        $currentSorting = $request->query->get('sort');

        // Мапим правило на поле
        $sort = match ($currentSorting) {
            self::SORTING_RULE_PRICE_ASC, self::SORTING_RULE_PRICE_DESC => 'p.price.listPrice',
            default => null,
        };

        // Мапим правило на направление сортирови
        $direction = match ($currentSorting) {
            self::SORTING_RULE_PRICE_DESC => 'desc',
            default => 'asc',
        };

        $listCategoryQueryBuilder = $products->listByCategoryQueryBuilder(
            category: $category,
            sort: $sort,
            direction: $direction,
            filterIds: $filterValues,
        );

        // Получаем номер страници
        $page = $request->get('page') !== null ? (int)$request->get('page') : 1;

        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: new QueryAdapter($listCategoryQueryBuilder),
            currentPage: $request->query->getInt('page', 1),
            maxPerPage: $request->query->getInt('size', self::PER_PAGE)
        );

        $filters = [];

        /** @deprecated */
        /** @var AttributeAssignment $attribute */
        foreach ($category->getAttributes() as $attribute) {
            if ($attribute->getAttribute()->isVisibleFilter()) {
                $filters[] = [
                    'id' => $attribute->getAttribute()->getId(),
                    'name' => $attribute->getAttribute()->getName(),
                    'items' => array_map(
                        static function (Variant $value) {
                            return [
                                'id' => $value->getId(),
                                'name' => $value->getName(),
                            ];
                        },
                        $attribute->getAttribute()->getVariants()->toArray()
                    ),
                ];
            }
        }

        return $this->render(
            'default/store/category/show.html.twig',
            [
                'metaData' => $this->metaData,
                'page' => $page,
                'menu' => $this->menu,
                'category' => $category,
                'breadcrumbs' => $this->breadcrumbsCategoryGenerate($category),
                'pagination' => $pagerfanta,
                'sorting_rules' => self::SORTING_RULES,
                'filters' => $filters,
                'filter_setting_ids' => ($filterSettingIds !== null) ? $this->decodeFilterIdsToArray(
                    $filterSettingIds
                ) : [null],
            ]
        );
    }

    private function decodeFilterIdsToArray(string $hash): array
    {
        $result = explode('_', $hash);
        sort($result);

        return array_map('intval', $result);
    }
}
