<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public const PER_PAGE= 15;

    #[Route(path: '/collections/test', name: 'store.collections')]
    public function index(Request $request, ProductRepository $products): Response
    {
        return $this->render(
            'store/category/show.html.twig',
            [
                'pagination' => $products->list(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
            ]
        );
    }
}
