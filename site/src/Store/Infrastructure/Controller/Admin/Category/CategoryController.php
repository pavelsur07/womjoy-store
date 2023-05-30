<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Category\ValueObject\CategoryImage;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Form\Category\CategoryEditForm;
use App\Store\Infrastructure\Form\Category\CategoryImageForm;
use App\Store\Infrastructure\Form\Category\CategorySeoEditForm;
use League\Flysystem\FilesystemException;
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
                'pagination' => $categories->getRootNodes(),
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

    #[Route('/{id}/add-child', name: '.add.child', methods: ['GET', 'POST'])]
    public function addChild(int $id, Category $category, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(CategoryEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $category->addSubCategory($data['name']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.category.edit', ['id'=> $category->getId()], Response::HTTP_SEE_OTHER);
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
            $category->generateIds();
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
    public function seo(Request $request, Category $category, Flusher $flusher, SlugifyService $slugify): Response
    {
        $form = $this->createForm(CategorySeoEditForm::class, [
            'h1' => $category->getSeoMetadata()->getH1(),
            'seoTitle' => $category->getSeoMetadata()->getSeoTitle(),
            'seoDescription' => $category->getSeoMetadata()->getSeoDescription(),
            'slug' => $category->getSlug(),
            'prefixSlugProduct' => $category->getPrefixSlugProduct(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $category->getSeoMetadata()->setH1($data['h1']);
            $category->getSeoMetadata()->setSeoTitle($data['seoTitle']);
            $category->getSeoMetadata()->setSeoDescription($data['seoDescription']);
            $category->setSlug($slugify->generate($data['slug']));
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

    #[Route('/{id}/remove', name: '.remove')]
    public function remove(int $id, Request $request, Category $category, CategoryRepositoryInterface $categories): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categories->remove($category, true);
        }
        $categories->remove($categories->get($id), true);
        return $this->redirectToRoute('store.admin.category.index');
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{id}/image', name: '.image')]
    public function addImage(Category $category, Request $request, FileUploader $uploader, Flusher $flusher): Response
    {
        $form = $this->createForm(CategoryImageForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $uploaded = $uploader->upload($file, $path = 'category');

            if ($category->getImage() !== null) {
                $uploader->remove(
                    path: $category->getImage()->getPath(),
                    name: $category->getImage()->getName()
                );
            }

            if ($category->getImage() === null) {
                $category->setImage(new CategoryImage());
            }

            $category->getImage()->update(
                path: $uploaded->getPath(),
                name: $uploaded->getName(),
                size: $uploaded->getSize()
            );

            $flusher->flush();
        }

        return $this->render(
            'store/admin/category/image/edit.html.twig',
            [
                'category'=> $category,
                'form' => $form->createView(),
            ]
        );
    }
}
