<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Service\Home\HomeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    public const PER_PAGE = 8;

    #[Route(path: '/', name: 'home', requirements: ['_locale' => 'en|ru|bg'])]
    public function show(Request $request, ProductRepository $products, HomeService $homes): Response
    {
        $home = $homes->get();
        $this->setTitle($home->getSeoMetadata()->getSeoTitle());
        $this->setDescription($home->getSeoMetadata()->getSeoDescription());
        $this->setH1($home->getSeoMetadata()->getH1());

        $locales = $request->getLocale();
        $pagination = $products->list(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'store/home/home.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
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
