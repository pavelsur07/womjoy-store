<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Exception\StoreProductException;
use App\Store\Infrastructure\Form\Product\ProductAggregateRatingEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.rating')]
class AggregateRatingController extends AbstractController
{
    #[Route(path: '/{id}/rating/edit', name: '.edit')]
    public function edit(int $id, Product $product, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ProductAggregateRatingEditForm::class,
            [
                'count' => $product->getRating()->getReviewCount(),
                'value' => $product->getRating()->getRatingValue(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $product->getRating()->setReviewCount($data['count']);
                $product->getRating()->setRatingValue($data['value']);
                $flusher->flush();
                $this->addFlash('success', 'Succes aggregate rating changed.');
            } catch (StoreProductException $e) {
                $this->addFlash('danger', 'Error aggregate rating changed. ' . $e->getMessage());
            }
        }
        return $this->render(
            'store/admin/product/rating/edit.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
    }
}
