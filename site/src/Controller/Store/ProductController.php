<?php

declare(strict_types=1);

namespace App\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    //#[Route(path: '/{_locale}/product/{slug}', name: 'store.product.show', requirements: ['_locale' => 'en|ru|bg'])]
    #[Route(path: '/{_locale}/product/{slug}', name: 'store.product.show',requirements: ['_locale' => 'en|ru|bg'])]
    public function show(string $slug): Response
    {
        return $this->render('store/product/show.html.twig');
    }
}
