<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Domain\Entity\Home\AssignCategory;
use App\Store\Domain\Entity\Home\Home;
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
        if ($home->getSeoMetadata()->getSeoTitle() !== null) {
            $this->setTitle($home->getSeoMetadata()->getSeoTitle());
        }
        if ($home->getSeoMetadata()->getSeoDescription() !== null) {
            $this->setDescription($home->getSeoMetadata()->getSeoDescription());
        }
        if ($home->getSeoMetadata()->getH1() !== null) {
            $this->setH1($home->getSeoMetadata()->getH1());
        }

        $locales = $request->getLocale();
        $newItems = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );

        $popularity = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );

        return $this->render(
            'store/home/home.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'categories' => $this->categories($home),
                'newItems' => $newItems,
                'popularity' => $popularity,
                'locales' => $locales,
            ]
        );
    }

    #[Route(path: '/locale/{locale}', name: 'locale', requirements: ['_locale' => 'en|ru|bg'])]
    public function locale(Request $request): Response
    {
        return $this->redirectToRoute('home');
    }

    private function categories(Home $home): array
    {
        $result = [];
        /** @var AssignCategory $category */
        foreach ($home->getCategories() as $category) {
            $result[] = [
                'name' => $category->getCategory()->getName(),
                'href' => $this->generateUrl('store.category.show', ['slug'=> $category->getCategory()->getSlug()]),
                'imagePath' => $category->getCategory()->getImage()->getPath() . '/' . $category->getCategory()->getImage()->getName(),
            ];
        }

        return $result;
    }
}
