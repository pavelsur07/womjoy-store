<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Repository\ColorRepositoryInterface;
use App\Matrix\Infrastructure\Form\ColorlEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/colors', name: 'matrix.admin.color')]
class ColorController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(ColorRepositoryInterface $colors): Response
    {
        return $this->render(
            'matrix/admin/color/index.html.twig',
            [
                'pagination' => $colors->list(),
            ]
        );
    }

    #[Route(path: '/create', name: '.create')]
    public function create(Request $request, ColorRepositoryInterface $colors): Response
    {
        $form = $this->createForm(ColorlEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $color = new Color(
                name: $data['name'],
            );

            $colors->save($color, true);

            return $this->redirectToRoute('matrix.admin.color.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/color/create.html.twig', ['form'=> $form->createView()]);
    }

    public function remove(): void {}

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Color $color, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ColorlEditForm::class,
            [
                'name'=> $color->getName(),
                'code' => $color->getCode(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $color->setName($data['name']);
            $color->setCode($data['code']);

            $flusher->flush();
            $this->addFlash('success', 'Changed success!');
            return $this->redirectToRoute('matrix.admin.color.edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/color/edit.html.twig', ['form'=> $form->createView(), 'color' => $color]);
    }
}
