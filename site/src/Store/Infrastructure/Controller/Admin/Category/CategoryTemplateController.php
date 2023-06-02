<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Form\Category\CategoryTemplateEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'store.admin.category.template')]
class CategoryTemplateController extends AbstractController
{
    #[Route(path: '/{id}/template', name: '.edit')]
    public function edit(Category $category, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            CategoryTemplateEditForm::class,
            [
                'title' => $category->getTitleProductTemplate(),
                'description' => $category->getDescriptionProductTemplate(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category->setTitleProductTemplate($data['title']);
            $category->setDescriptionProductTemplate($data['description']);
            $flusher->flush();

            $this->addFlash('success', 'Success changed templates.');
        }

        return $this->render(
            'store/admin/category/template/edit.html.twig',
            [
                'category'=> $category,
                'form' => $form->createView(),
                'breadcrumbs' => $this->breadcrumbs($category),
            ]
        );
    }

    private function breadcrumbs(Category $category, ?array $bread = null): array
    {
        $bread[] = $category;

        if ($category->getParent() !== null) {
            return $this->breadcrumbs($category->getParent(), $bread);
        }

        return array_reverse($bread);
    }
}
