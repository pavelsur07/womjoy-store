<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Infrastructure\Form\Category\CategoryMenuAddItemForm;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'store.admin.category.menu')]
class CategoryMenuController extends AbstractController
{
    #[Route(path: '/{id}/menu', name: '.index')]
    public function index(Category $category, Request $request): Response
    {
        return $this->render(
            'admin/store/category/menu/index.html.twig',
            [
                'category'=> $category,
            ]
        );
    }

    #[Route(path: '/{id}/menu/add-item', name: '.add_item')]
    public function addItem(Category $category, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(CategoryMenuAddItemForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category->getMenu()->addItem(
                itemId: Uuid::uuid4()->toString(),
                name: $data['name'],
                href: $data['href']
            );

            $flusher->flush();
            $this->addFlash('success', 'Success changed templates.');
            return $this->redirectToRoute('store.admin.category.menu.index', ['id' =>$category->getId()]);
        }

        return $this->render(
            'admin/store/category/menu/edit.html.twig',
            [
                'category'=> $category,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/menu/remove-item/{itemId}', name: '.remove_item')]
    public function removeItem(Category $category, string $itemId, Request $request, Flusher $flusher): Response
    {
        $category->getMenu()->removeItem($itemId);
        $flusher->flush();

        $this->addFlash('success', 'Success changed templates.');
        return $this->redirectToRoute('store.admin.category.menu.index', ['id' =>$category->getId()]);
    }
}
