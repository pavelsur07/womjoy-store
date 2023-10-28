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

#[Route('/admin/product', name: 'store.admin.product.related_color')]
class RelatedColorController extends AbstractController
{
    public const PER_PAGE= 10;

    #[Route(path: '/{id}/related-color/', name: '.index')]
    public function index(int $id, Product $product, Request $request, ProductRepository $products): Response
    {
        $pagination = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'admin/store/product/related_color/index.html.twig',
            [
                'pagination' => $pagination,
                'product' => $product,
            ]
        );
    }

    #[Route(path: '/{id}/related-color/{assign_id}/assign', name: '.assign')]
    public function assign(int $id, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        try {
            $productAssign = $products->get((int)$request->get('assign_id'));

            $product->assignRelatedColors($productAssign);
            $productAssign->assignRelatedColors($product);

            foreach ($product->getRelatedColors() as $relatedColor) {
                $relatedColor->assignRelatedColors($productAssign);
            }

            foreach ($product->getRelatedColors() as $relatedColor) {
                $productAssign->assignRelatedColors($relatedColor);
            }

            $flusher->flush();
            $this->addFlash('success', 'Success related colors - ' . $product->getName() . '.');
        } catch (StoreProductException $e) {
            $this->addFlash('danger', 'Error related color. ' . $e->getMessage() . $product->getName() . '.');
        }

        return $this->redirectToRoute('store.admin.product.related_color.index', ['id' => $id]);
    }

    #[Route(path: '/{id}/related-color/{revoke_id}/revoke', name: '.revoke')]
    public function revoke(int $id, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        try {
            $productRevoke = $products->get((int)$request->get('revoke_id'));

            $product->revokeRelatedColors($productRevoke);
            $productRevoke->revokeRelatedColors($product);

            foreach ($product->getRelatedColors() as $relatedColor) {
                $relatedColor->revokeRelatedColors($productRevoke);
            }

            foreach ($product->getRelatedColors() as $relatedColor) {
                $productRevoke->revokeRelatedColors($relatedColor);
            }

            $flusher->flush();
            $this->addFlash('success', 'Success related revoke - ' . $product->getName() . '.');
        } catch (StoreProductException $e) {
            $this->addFlash('danger', 'Error related revoke. ' . $e->getMessage() . ' ' . $product->getName() . '.');
        }
        return $this->redirectToRoute('store.admin.product.related_color.index', ['id' => $id]);
    }
}
