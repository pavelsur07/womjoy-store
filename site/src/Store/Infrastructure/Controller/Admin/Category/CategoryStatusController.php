<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Exception\StoreCategoryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'store.admin.category.status')]
class CategoryStatusController extends AbstractController
{
    #[Route(path: '/{id}/status/active', name: '.active')]
    public function active(int $id, Category $category, Flusher $flusher): Response
    {
        try {
            $category->active();
            $flusher->flush();
            $this->addFlash('success', 'Success active category.');
        } catch (StoreCategoryException $e) {
            $this->addFlash('danger', 'Error active category - ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.admin.category.edit', ['id'=> $id]);
    }

    #[Route(path: '/{id}/status/disable', name: '.disable')]
    public function disable(int $id, Category $category, Flusher $flusher): Response
    {
        try {
            $category->disable();
            $flusher->flush();
            $this->addFlash('success', 'Success disable category.');
        } catch (StoreCategoryException $e) {
            $this->addFlash('danger', 'Error disable category - ' . $e->getMessage());
        }
        return $this->redirectToRoute('store.admin.category.edit', ['id'=> $id]);
    }
}
