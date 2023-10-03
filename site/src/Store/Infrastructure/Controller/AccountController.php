<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AccountController extends BaseController
{
    #[Route(path: '/account/history', name: 'store.account.history')]
    public function index(): Response
    {
        return $this->render(
            'default/store/account/history.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}
