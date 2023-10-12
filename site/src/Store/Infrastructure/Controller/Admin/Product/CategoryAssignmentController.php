<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Form\Product\ProductCategoryAssignmentForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{product_id}/categories', name: 'store.admin.product.categories')]
class CategoryAssignmentController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(Request $request, CategoryRepositoryInterface $categories, ProductRepository $products): Response
    {
        $product = $products->get((int)$request->get('product_id'));

        $form = $this->createForm(ProductCategoryAssignmentForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute(
                'store.admin.product.categories.assign',
                [
                    'product_id' => $product->getId(),
                    'id' => $data['category']->getValue(),
                ]
            );
        }

        return $this->render(
            'admin/store/product/categories/index.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{id}/assign', name: '.assign')]
    public function assign(
        int $id,
        Request $request,
        ProductRepository $products,
        CategoryRepositoryInterface $categories,
        Flusher $flusher
    ): Response {
        $productId = (int)$request->get('product_id');

        try {
            $product = $products->get($productId);
            $category = $categories->get($id);

            $product->assignCategory($category);

            $flusher->flush();

            $this->addFlash('success', 'Success assign category ' . $id);
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error assign category ' . $id);
        }

        return $this->redirectToRoute('store.admin.product.categories.index', ['product_id' => $productId]);
    }

    #[Route('/{id}/revoke', name: '.revoke')]
    public function revoke(
        int $id,
        Request $request,
        ProductRepository $products,
        Flusher $flusher,
    ): Response {
        $productId = (int)$request->get('product_id');

        try {
            $product = $products->get($productId);

            $product->revokeCategory($id);

            $flusher->flush();

            $this->addFlash('success', 'Success revoke category ' . $id);
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error revoke category ' . $id);
        }

        return $this->redirectToRoute('store.admin.product.categories.index', ['product_id' => $productId]);
    }
}
