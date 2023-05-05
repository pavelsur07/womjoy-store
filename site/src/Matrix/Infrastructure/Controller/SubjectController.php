<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller;

use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Repository\SubjectRepositoryInterface;
use App\Matrix\Infrastructure\Form\SubjectEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/subjects', name: 'matrix.admin.subject')]
class SubjectController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(SubjectRepositoryInterface $subjects): Response
    {
        return $this->render(
            'admin/matrix/subject/index.html.twig',
            [
                'pagination' => $subjects->list(),
            ]
        );
    }

    #[Route(path: '/create', name: '.create')]
    public function create(Request $request, SubjectRepositoryInterface $subjects): Response
    {
        $form = $this->createForm(SubjectEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $subject = new Subject(
                name: $data['name'],
            );

            $subjects->save($subject, true);

            return $this->redirectToRoute('matrix.admin.subject.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/matrix/subject/create.html.twig', ['form'=> $form->createView()]);
    }

    public function remove(): void
    {
    }

    public function edit(): void
    {
    }
}
