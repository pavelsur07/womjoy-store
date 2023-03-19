<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Entity\User\User;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class RegistrationUserController extends AbstractController
{
    #[Route(path: '/registration/', name: 'store.registration')]
    public function checkout(Request $request, UserPasswordHasherInterface $hasher, UserRepository $users): Response
    {
        $form = $this->createForm(UserRegistrationType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            Assert::email($data['email']);

            $password = $data['password'];
            $email = $data['email'];

            $user = new User();
            $user->setEmail($email);
            $hashed = $hasher->hashPassword(
                user: $user,
                plainPassword: $password
            );
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($hashed);

            $users->save($user, true);
            $this->addFlash('success', 'Registration new user.');
        }

        return $this->render(
            'auth/registration.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
