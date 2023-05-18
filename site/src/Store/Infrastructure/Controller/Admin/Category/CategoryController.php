<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Form\Category\CategoryEditForm;
use App\Store\Infrastructure\Form\Category\CategorySeoEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'store.admin.category')]
class CategoryController extends AbstractController
{
    public const PER_PAGE= 15;

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(CategoryRepositoryInterface $categories): Response
    {
        return $this->render(
            'store/admin/category/index.html.twig',
            [
                'pagination' => $categories->list(),
            ]
        );
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepositoryInterface $categories, SlugifyService $service): Response
    {
        $form = $this->createForm(CategoryEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $category = new Category();
            $category->setName($data['name']);
            $category->setSlug($service->generate($data['name']));
            $category->setPrefixSlugProduct($service->generate($data['name']));

            $categories->save($category, true);

            return $this->redirectToRoute('store.admin.category.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render(
            'store/admin/category/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, Flusher $flusher): Response
    {
        $form = $this->createForm(CategoryEditForm::class, [
            'name' => $category->getName(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $category->setName($data['name']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.category.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render(
            'store/admin/category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    #[Route('/{id}/seo', name: '.seo', methods: ['GET', 'POST'])]
    public function seo(Request $request, Category $category, Flusher $flusher)
    {
        $form = $this->createForm(CategorySeoEditForm::class, [
            'slug' => $category->getSlug(),
            'prefixSlugProduct' => $category->getPrefixSlugProduct(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $category->setName($data['name']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.category.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render(
            'store/admin/category/seo.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
