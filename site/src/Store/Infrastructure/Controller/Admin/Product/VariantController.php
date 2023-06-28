<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\Product\ProductVariantAddForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.variant')]
class VariantController extends AbstractController
{
    #[Route(path: '/{id}/variant/add', name: '.add')]
    public function add(int $id, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        $form = $this->createForm(ProductVariantAddForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->addVariant($data['value']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
        }
        return $this->render(
            'store/admin/product/variant/add.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }
}
