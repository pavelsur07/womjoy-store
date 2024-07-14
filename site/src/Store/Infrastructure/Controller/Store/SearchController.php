<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use App\Store\Infrastructure\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    public const PER_PAGE = 16;

    public const SORTING_RULE_POPULARITY = 'popularity';
    public const SORTING_RULE_PRICE_ASC = 'price-asc';
    public const SORTING_RULE_PRICE_DESC = 'price-desc';

    public const SORTING_RULES = [
        self::SORTING_RULE_POPULARITY => 'По популярности',
        self::SORTING_RULE_PRICE_ASC => 'По возрастанию цены',
        self::SORTING_RULE_PRICE_DESC => 'По убыванию цены',
    ];

    #[Route(path: '/search', name: 'search')]
    public function search(Request $request, ProductRepository $products): Response
    {
        $listSearchQueryBuilder = $products->search($request->get('param'));

        // Получаем номер страници
        $page = $request->get('page') !== null ? (int)$request->get('page') : 1;

        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: new QueryAdapter($listSearchQueryBuilder),
            currentPage: $request->query->getInt('page', 1),
            maxPerPage: $request->query->getInt('size', self::PER_PAGE)
        );

        $popularity = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
            direction: 'asc',
            status: ProductStatus::ACTIVE,
        );

        return $this->render(
            "{$this->template}/store/search/index.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'referer' => $request->headers->get('referer'),
                'param'=> $request->get('param'),
                'pagination' => $pagerfanta,
                'popularity'=> $popularity,
                'sorting_rules' => self::SORTING_RULES,
                /*'filters' => $filters,
                'filter_setting_ids' => ($filterSettingIds !== null) ? $this->decodeFilterIdsToArray(
                    $filterSettingIds
                ) : [null],*/
            ]
        );
    }
}
