<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Repository\ModelRepositoryInterface;
use App\Matrix\Infrastructure\Form\ModelEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/models', name: 'matrix.admin.model')]
class ModelController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(ModelRepositoryInterface $models): Response
    {
        return $this->render(
            'matrix/admin/model/index.html.twig',
            [
                'pagination' => $models->list(),
            ]
        );
    }

    #[Route(path: '/create', name: '.create')]
    public function create(Request $request, ModelRepositoryInterface $models): Response
    {
        $form = $this->createForm(ModelEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $model = new Model(
                name: $data['name'],
            );

            $models->save($model, true);

            return $this->redirectToRoute('matrix.admin.model.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/model/create.html.twig', ['form'=> $form->createView()]);
    }

    public function remove(): void
    {
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Model $model, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ModelEditForm::class,
            [
                'name' => $model->getName(),
                'code' => $model->getCode(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $model->setName($data['name']);
            $model->setCode($data['code']);
            $flusher->flush();
            $this->addFlash('success', 'Changed success!');
            return $this->redirectToRoute('matrix.admin.model.edit', ['id'=> $id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/model/edit.html.twig', ['form'=> $form->createView(), 'model'=> $model]);
    }
}
