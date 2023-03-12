<?php

namespace App\Controller\Store;

use App\Entity\User\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InstallController extends AbstractController
{
    #[Route(path: '/install/3038b048-1012-4a81-83a0-d3f800e08b2f/create-admin/', name: 'store.install')]
    public function checkout(UserPasswordHasherInterface $hasher, UserRepository $users): Response
    {
        $user = new User();
        $user->setEmail('pavelsur07@gmail.com');
        $hashed = $hasher->hashPassword(
            user: $user,
            plainPassword: 'passwordSecret'
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($hashed);
        $users->save($user, true);

        return $this->redirectToRoute('admin.dashboard.show');
    }
}