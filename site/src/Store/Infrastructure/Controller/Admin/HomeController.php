<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\Home\HomeEditForm;
use App\Store\Infrastructure\Service\Home\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/home', name: 'store.admin.home')]
class HomeController extends AbstractController
{
    #[Route(path: '/edit', name: '.edit')]
    public function edit(Request $request, HomeService $homeService, Flusher $flusher): Response
    {
        $home = $homeService->get();

        $form = $this->createForm(HomeEditForm::class, [
            'h1'=> $home->getSeoMetadata()->getH1(),
            'title' => $home->getSeoMetadata()->getSeoTitle(),
            'description' => $home->getSeoMetadata()->getSeoDescription(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $home->getSeoMetadata()->setH1($data['h1']);
            $home->getSeoMetadata()->setSeoTitle($data['title']);
            $home->getSeoMetadata()->setSeoDescription($data['description']);

            $flusher->flush();

            $this->addFlash('success', 'Success edit home page.');

            return $this->redirectToRoute('store.admin.home.edit');
        }

        return $this->render(
            'store/admin/home/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
