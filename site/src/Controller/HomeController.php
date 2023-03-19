<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public const PER_PAGE = 8;

    #[Route(path: '/', name: 'home')]
    public function show(Request $request, ProductRepository $products): Response
    {
        $pagination = $products->list(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'store/home.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }
}
