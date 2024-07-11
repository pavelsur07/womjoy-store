<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Admin;

use App\Auth\Domain\Entity\User;
use App\Auth\Infrastructure\Form\InstallForm;
use App\Auth\Infrastructure\Form\InstallSetAdminForm;
use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class InstallController extends BaseController
{
    #[Route('/install/60c64ad9-c05f-41cb-bb0a-e262bf74be1d/', name: 'install')]
    public function install(Request $request, UserRepository $users, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(InstallForm::class, []);

        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if (\count($users->findAll()) > 0) {
                $this->addFlash('danger', 'Service is already installed.');
                return $this->redirectToRoute('install');
            }

            $data = $form->getData();

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

            $user->setPassword($hashed);

            $user->getRoles(['ROLE_ADMIN']);

            $users->save($user, true);
            $this->addFlash('success', 'Registration new user.');
            return $this->redirectToRoute('account.dashboard');
        }

        return $this->render(
            'admin/install/install.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'error' => $error,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/install/set-admin/60c64ad9-c05f-41cb-bb0a-e262bf74be1d/', name: 'install.set_admin')]
    public function setAdmin(Request $request, UserRepository $users, Flusher $flusher): Response
    {
        $form = $this->createForm(InstallSetAdminForm::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (\count($users->findAll()) > 1) {
                $this->addFlash('danger', 'Service is already installed.');
                return $this->redirectToRoute('install');
            }

            $user = $users->getByEmail($data['email']);
            $user->setRoles(['ROLE_ADMIN']);
            $flusher->flush();

        }

        /*return $this->redirectToRoute('admin.dashboard.show');*/
        return $this->render('admin/install/set-admin.html.twig', ['form' => $form->createView()]);
    }
}
