<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Repository\AttributeRepository;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category/{id}/attributes', name: 'store.admin.category.attribute')]
class AttributeController extends AbstractController
{
    public const PER_PAGE = 15;

    #[Route(path: '/', name: '.edit')]
    public function edit(Request $request, CategoryRepositoryInterface $categories, AttributeRepository $attributes): Response
    {
        $category = $categories->get((int)$request->get('id'));

        $pagination = $attributes->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );

        return $this->render(
            'admin/store/category/attribute/edit.html.twig',
            [
                'category' => $category,
                'pagination' => $pagination,
            ]
        );
    }

    #[Route(path: '/assign/{attributeId}', name: '.assign')]
    public function assign(
        int $attributeId,
        Request $request,
        CategoryRepositoryInterface $categories,
        AttributeRepository $attributes,
        Flusher $flusher
    ): Response {
        $category = $categories->get((int)$request->get('id'));
        $attribute = $attributes->get($attributeId);

        try {
            $category->assignAttribute($attribute);
            $flusher->flush();
            $this->addFlash('success', 'Success assign attribute.');
        } catch (DomainException $e) {
            $this->addFlash('danger', 'Error assign attribute - ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.admin.category.attribute.edit', ['id' => $category->getId()]);
    }

    #[Route(path: '/revoke/{attributeId}', name: '.revoke')]
    public function revoke(
        int $attributeId,
        Request $request,
        CategoryRepositoryInterface $categories,
        AttributeRepository $attributes,
        Flusher $flusher
    ): Response {
        $category = $categories->get((int)$request->get('id'));
        $attribute = $attributes->get($attributeId);

        try {
            $category->revokeAttribute($attribute->getId());
            $flusher->flush();
            $this->addFlash('success', 'Success revoke attribute.');
        } catch (DomainException $e) {
            $this->addFlash('danger', 'Error revoke attribute - ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.admin.category.attribute.edit', ['id' => $category->getId()]);
    }
}
