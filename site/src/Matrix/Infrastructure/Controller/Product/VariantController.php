<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\ValueObject\VariantBarcode;
use App\Matrix\Domain\Entity\ValueObject\VariantValue;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use App\Matrix\Infrastructure\Form\VariantEditForm;
use DomainException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/products/{product_id}/variant', name: 'matrix.admin.product.variant')]
class VariantController extends AbstractController
{
    #[Route(path: '/add', name: '.add')]
    public function add(Request $request, ProductRepositoryInterface $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $product = $products->get($productId);

        $form = $this->createForm(VariantEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $barcode = $data['barcode'];
                if ($barcode === null) {
                    $this->addFlash('success', 'Ups!.');
                    return $this->redirectToRoute('matrix.admin.product.edit', ['id' => $productId]);
                }

                $product->addVariant(
                    barcode: new VariantBarcode($data['barcode']),
                    value: new VariantValue($data['value'])
                );

                $flusher->flush();
                $this->addFlash('success', 'Success added variant product.');
            } catch (Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }

            return $this->redirectToRoute('matrix.admin.product.edit', ['id' => $productId]);
        }

        return $this->render(
            'matrix/admin/product/variant/add.html.twig',
            [
                'form'=> $form->createView(),
                'product' => $product,
            ]
        );
    }

    #[Route(path: '/{id}/barcode/generate', name: '.barcode.generate')]
    public function generateBarcode(int $id, Request $request, ProductRepositoryInterface $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        try {
            $product = $products->get($productId);
            $variant = $product->getVariant(variantId: $id);
            $variant->generateInternalBarcode();
            $flusher->flush();
            $this->addFlash('success', 'Success generate barcode.');
        } catch (MatrixException|DomainException $e) {
            $this->addFlash('warning', $e->getMessage());
        }

        return $this->redirectToRoute('matrix.admin.product.edit', ['id'=> $productId]);
    }

    public function remove(): void {}
}
