<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller\Admin;

use App\Auth\Domain\Entity\User;
use App\Auth\Infrastructure\Form\UserChangePasswordForm;
use App\Auth\Infrastructure\Form\UserType;
use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Doctrine\Flusher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app.user.index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.user.new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app.user.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app.user.show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/set-role-admin', name: 'app.user.set_role_admin', methods: ['GET'])]
    public function setRoleAdmin(int $id, Request $request, UserRepository $users, Flusher $flusher): Response
    {
        $user = $users->get($id);
        $user->setRoles(['ROLE_MANAGER']);
        $flusher->flush();
        return $this->redirectToRoute('app.user.index');
    }

    #[Route('/{id}/edit', name: 'app.user.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app.user.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/change-password', name: 'app.user.change_password')]
    public function changePassword(int $id, Request $request, UserRepository $users, UserPasswordHasherInterface $hasher): Response
    {
        $user = $users->get($id);

        $form = $this->createForm(UserChangePasswordForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $hashed = $hasher->hashPassword(
                user: $user,
                plainPassword: $data['password']
            );
            $user->setPassword($hashed);
            $users->flush();

            return $this->redirectToRoute('app.user.index');
        }

        return $this->render(
            'admin/user/change_password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{id}', name: 'app.user.delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app.user.index');
    }
}
