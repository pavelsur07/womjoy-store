<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    public const PER_PAGE= 15;

    #[Route(path: '/catalog/{slug}', name: 'store.category.show')]
    public function index(string $slug, Category $category, Request $request, ProductRepository $products): Response
    {
        return $this->render(
            'store/category/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'category' => $category,
                'breadcrumbs'=> $this->breadcrumbsCategoryGenerate($category),
                'pagination' => $products->list(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
            ]
        );
    }
}
