<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Account;

use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/account', name: 'account')]
class DashboardController extends BaseController
{
    #[Route(path: '/user', name: '.dashboard')]
    public function dashboard(UserRepository $users): Response
    {
        $user = $users->getByEmail($this->getUser()->getUserIdentifier());
        return $this->render(
            'pion/account/dashboard/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'user' => $user
            ]
        );
    }
}
