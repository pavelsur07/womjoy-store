<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Form\Product\ProductReviewEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.review')]
class ReviewController extends AbstractController
{
    #[Route(path: '/{id}/review/', name: '.index')]
    public function index(int $id, Product $product, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ProductReviewEditForm::class,
            [
                'customerName' => '',
                'vote' => 1,
                'text' => '',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->addReview(
                vote: $data['vote'],
                text: $data['text'],
                customerName: $data['customerName']
            );

            $flusher->flush();
        }

        return $this->render(
            'admin/store/product/review/index.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
    }
}
