<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Store\Domain\Entity\Product\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.related_assignment')]
class RelatedAssignmentController extends AbstractController
{
    #[Route(path: '/{id}/related-assignment/', name: '.index')]
    public function index(int $id, Product $product): Response
    {
        return $this->render(
            'store/admin/product/related/index.html.twig',
            [
                'product' => $product,
                'items' => $product->getRelatedAssignments()->toArray(),
            ]
        );
    }

    #[Route(path: '/{id}/related-assignment/assign', name: '.assign')]
    public function assign(int $id): Response
    {
        return $this->redirectToRoute('store.admin.product.related_assignment.index', ['id' => $id]);
    }

    #[Route(path: '/{id}/related-assignment/revoke', name: '.revoke')]
    public function revoke(int $id): Response
    {
        return $this->redirectToRoute('store.admin.product.related_assignment.index', ['id' => $id]);
    }
}
