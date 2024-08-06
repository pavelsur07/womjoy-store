<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\Product\ProductVariantAddForm;
use App\Store\Infrastructure\Form\Product\ProductVariantEditForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Repository\VariantRepository;
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
            $product->addVariant($data['value'], $data['barcode']);
            $flusher->flush();
            $this->addFlash('success', 'Product variant added successfully');
            return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
        }
        return $this->render(
            'admin/store/product/variant/add.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    #[Route(path: '/{id}/variant/{variantId}/remove', name: '.remove')]
    public function remove(int $id, int $variantId, Request $request, ProductRepository $products, VariantRepository $variants, Flusher $flusher): Response
    {
        $variant = $variants->get($variantId);
        $product = $products->get($id);
        $product->removeVariant($variant);

        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
    }

    #[Route(path: '/{id}/variant/{variantId}/edit', name: '.edit')]
    public function edit(int $id, int $variantId, Request $request, VariantRepository $variants, Flusher $flusher): Response
    {
        $variant = $variants->get($variantId);
        $form = $this->createForm(
            ProductVariantEditForm::class,
            [
                'value' => $variant->getValue(),
                'quantity' => $variant->getQuantity(),
                'barcode' => $variant->getBarcode(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $variant->changeQuantity($data['quantity']);
            $variant->changeBarcode($data['barcode']);
            $variant->changeValue($data['value']);

            $flusher->flush();
            return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
        }
        return $this->render(
            'admin/store/product/variant/edit.html.twig',
            [
                'form' => $form->createView(),
                'variant' => $variant,
            ]
        );
    }
}
