<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Account;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/account/favorite', name: 'account.favorite')]
class FavoritesController extends BaseController
{
    #[Route(path: '/', name: '.index')]
    public function index(): Response
    {
        return $this->render(
            'pion/account/favorites/index.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}
