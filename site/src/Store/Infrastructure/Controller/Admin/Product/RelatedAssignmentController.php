<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Exception\StoreProductException;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.related_assignment')]
class RelatedAssignmentController extends AbstractController
{
    public const PER_PAGE= 10;

    #[Route(path: '/{id}/related-assignment/', name: '.index')]
    public function index(int $id, Product $product, Request $request, ProductRepository $products): Response
    {
        $pagination = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'store/admin/product/related/index.html.twig',
            [
                'pagination' => $pagination,
                'product' => $product,
            ]
        );
    }

    #[Route(path: '/{id}/related-assignment/{assign_id}/assign', name: '.assign')]
    public function assign(int $id, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        try {
            $product->assignRelatedProduct($products->get((int)$request->get('assign_id')));
            $flusher->flush();
            $this->addFlash('success', 'Success related assignment - ' . $product->getName() . '.');
        } catch (StoreProductException $e) {
            $this->addFlash('danger', 'Error related assignment. ' . $e->getMessage() . $product->getName() . '.');
        }

        return $this->redirectToRoute('store.admin.product.related_assignment.index', ['id' => $id]);
    }

    #[Route(path: '/{id}/related-assignment/{revoke_id}/revoke', name: '.revoke')]
    public function revoke(int $id, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        try {
            $product->revokeRelatedProduct((int)$request->get('revoke_id'));
            $flusher->flush();
            $this->addFlash('success', 'Success related revoke - ' . $product->getName() . '.');
        } catch (StoreProductException $e) {
            $this->addFlash('danger', 'Error related revoke. ' . $e->getMessage() . ' ' . $product->getName() . '.');
        }
        return $this->redirectToRoute('store.admin.product.related_assignment.index', ['id' => $id]);
    }
}
