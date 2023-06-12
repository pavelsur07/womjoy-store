<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/pages', name: 'page')]
class PageController extends BaseController
{
    public const PER_PAGE= 15;

    #[Route(path: '/pay-and-delivery', name: '.pay_delivery')]
    public function payAndDelivery(Request $request, ProductRepository $products): Response
    {
        $this->setTitle('Способы оплаты, стоимость и сроки доставки в интернет-магазине Womjoy');
        $this->setTitle('Узнайте о наших условиях доставки: сроки, стоимость, способы оплаты. Быстрая и надежная доставка по всей стране. Гарантия сохранности товара.');
        return $this->render(
            'page/pay_delivery.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ],
        );
    }

    #[Route(path: '/privacy', name: '.privacy')]
    public function privacy(): Response
    {
        $this->setTitle('Политика обработки персональных данных в магазине Womjoy');
        $this->setDescription('Узнайте о нашей политике конфеденциальности интернет-магазина womjoy.');
        return $this->render(
            'page/privacy.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ],
        );
    }

    #[Route(path: '/terms-of-service', name: '.terms_of_service')]
    public function termsOfService(): Response
    {
        $this->setTitle('Публичная оферта в интернет-магазине Womjoy');
        $this->setDescription('Ознакомтесь с публичной офертой интернет-магазина WOMJOY.');
        return $this->render(
            'page/terms_of_service.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ],
        );
    }
}
