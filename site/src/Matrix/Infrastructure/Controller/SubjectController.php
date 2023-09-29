<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
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
            'matrix/admin/subject/index.html.twig',
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
        return $this->render('matrix/admin/subject/create.html.twig', ['form'=> $form->createView()]);
    }

    public function remove(): void {}

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Subject $subject, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            SubjectEditForm::class,
            [
                'name' => $subject->getName(),
                'code' => $subject->getCode(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $subject->changeName($data['name']);
            $subject->setCode($data['code']);

            $flusher->flush();

            $this->addFlash('success', 'Changed success!');
            return $this->redirectToRoute('matrix.admin.subject.edit', ['id'=> $id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/subject/edit.html.twig', ['form'=> $form->createView(), 'subject'=> $subject]);
    }
}
