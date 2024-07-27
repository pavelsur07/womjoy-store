<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Controller\Admin;

use App\Banner\Domain\Entity\Banner;
use App\Banner\Infrastructure\Form\Admin\BannerNewForm;
use App\Banner\Infrastructure\Repository\BannerRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/banners', name: 'admin.banner')]
class BannerController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(BannerRepository $banners): Response
    {
        return $this->render(
            'admin/banner/index.html.twig',
            [
                'pagination' => $banners->getAll(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, BannerRepository $banners): Response
    {
        $form = $this->createForm(BannerNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $banners->save(
                new Banner(
                    id: Uuid::uuid4()->toString(),
                    name: $data['name']
                ),
                true
            );
            $this->addFlash('success', 'Banner created.');
            return $this->redirectToRoute('admin.banner.index');
        }
        return $this->render(
            'admin/banner/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
