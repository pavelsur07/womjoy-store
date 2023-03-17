<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InstallController extends AbstractController
{
    #[Route(path: '/install/install-admin/', name: 'store.install')]
    public function checkout(UserPasswordHasherInterface $hasher, UserRepository $users): Response
    {
        return $this->redirectToRoute('admin.dashboard.show');
    }
}