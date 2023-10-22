<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Form\Home\HomeAssignCategoryForm;
use App\Store\Infrastructure\Form\Home\HomeEditForm;
use App\Store\Infrastructure\Service\Home\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/home', name: 'store.admin.home')]
class HomeController extends AbstractController
{
    public function __construct(
        private readonly HomeService $homeService,
        private readonly Flusher $flusher
    ) {}

    #[Route(path: '/edit', name: '.edit')]
    public function edit(Request $request, Flusher $flusher): Response
    {
        $home = $this->homeService->get();

        $form = $this->createForm(HomeEditForm::class, [
            'h1'=> $home->getSeoMetadata()->getH1(),
            'title' => $home->getSeoMetadata()->getSeoTitle(),
            'description' => $home->getSeoMetadata()->getSeoDescription(),
            'isActiveSeoText' => $home->isActiveSeoText(),
            'seoText' => $home->getSeoText(),
            'isActiveBestseller' =>$home->isActiveBestseller(),
            'hrefBestseller' => $home->getHrefBestseller(),
            'isActiveNewProduct' => $home->isActiveNewArrivals(),
            'hrefNewProduct' => $home->getHrefNewArrivals(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $home->getSeoMetadata()->setH1($data['h1']);
            $home->getSeoMetadata()->setSeoTitle($data['title']);
            $home->getSeoMetadata()->setSeoDescription($data['description']);
            $home->setIsActiveSeoText($data['isActiveSeoText']);
            $home->setSeoText($data['seoText']);

            $home->setHrefBestseller($data['hrefBestseller']);
            $home->setIsActiveBestseller($data['isActiveBestseller']);

            $home->setHrefNewArrivals($data['hrefNewProduct']);
            $home->setIsActiveNewArrivals($data['isActiveNewProduct']);

            $flusher->flush();

            $this->addFlash('success', 'Success edit home page.');

            return $this->redirectToRoute('store.admin.home.edit');
        }

        return $this->render(
            'admin/store/home/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/categories', name: '.categories')]
    public function categories(Request $request, CategoryRepositoryInterface $categories): Response
    {
        $home = $this->homeService->get();
        $form = $this->createForm(HomeAssignCategoryForm::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $home->assignCategory($categories->get((int)$data['category']->getValue()));
            $this->flusher->flush();
            return $this->redirectToRoute('store.admin.home.categories');
        }
        return $this->render(
            'admin/store/home/categories/index.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $home->getCategories(),
            ]
        );
    }

    #[Route(path: '/categories/{id}', name: '.categories.revoke')]
    public function revokeCategory(int $id, Request $request): Response
    {
        $home = $this->homeService->get();
        $home->revokeCategory($id);
        $this->flusher->flush();
        return $this->redirectToRoute('store.admin.home.categories');
    }
}
