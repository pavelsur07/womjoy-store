<?php

declare(strict_types=1);

namespace App\Http\Store;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public const PER_PAGE = 8;

    #[Route(path: '/', name: 'home', requirements: ['_locale' => 'en|ru|bg'])]
    public function show(Request $request, ProductRepository $products): Response
    {
        $locales = $request->getLocale();
        $pagination = $products->list(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'store/home.html.twig',
            [
                'pagination' => $pagination,
                'locales' => $locales,
            ]
        );
    }

    #[Route(path: '/locale/{locale}', name: 'locale', requirements: ['_locale' => 'en|ru|bg'])]
    public function locale(Request $request): Response
    {
        return $this->redirectToRoute('home');
    }
}
