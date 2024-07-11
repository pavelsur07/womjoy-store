<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Domain\Entity\User;
use App\Auth\Infrastructure\Form\UserRegistrationType;
use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Controller\BaseController;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class RegistrationUserController extends BaseController
{
    #[Route(path: '/registration/', name: 'auth.registration')]
    public function checkout(Request $request, UserPasswordHasherInterface $hasher, UserRepository $users): Response
    {
        $form = $this->createForm(UserRegistrationType::class, []);
        $form->handleRequest($request);

        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                Assert::email($data['email']);
                Assert::notEmpty($data['firstName']);
                Assert::notEmpty($data['phone']);
                Assert::notEmpty($data['password']);
                Assert::minLength($data['password'], 8);

                $password = $data['password'];
                $plaintextPassword = $data['password'];

                if ($password !== $plaintextPassword) {
                    throw new DomainException('Error Plaintext Password!');
                }

                if ($users->hasEmail($data['email'])) {
                    throw new DomainException('User with such an e-mail address already exists');
                }

                if ($users->hasPhone($data['phone'])) {
                    throw new DomainException('User with such an phone already exists');
                }

                $user = User::create(
                    email: $data['email'],
                    firstName: $data['firstName'],
                    lastName: '',
                    phone: $data['phone']
                );

                $hashed = $hasher->hashPassword(
                    user: $user,
                    plainPassword: $password
                );

                // $user->setRoles(['ROLE_USER']);
                $user->setPassword($hashed);

                $users->save($user, true);
                $this->addFlash('success', 'Registration new user.');
                return $this->redirectToRoute('account.dashboard');
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render(
            'pion/auth/registration.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'error' => $error,
                'form' => $form->createView(),
            ]
        );
    }

}
