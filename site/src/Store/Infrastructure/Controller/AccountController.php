<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route(path: '/account/', name: 'store.account')]
    public function index(): Response
    {
        return $this->render('store/account/dashboard.html.twig');
    }
}
