<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Product\ValueObject\ProductDimensions;
use App\Store\Infrastructure\Form\Product\ProductDimensionForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{product_id}/dimension', name: 'store.admin.product.dimension')]
class ProductDimensionController extends AbstractController
{
    #[Route('/', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ProductRepository $products,
        FileUploader $uploader,
        Flusher $flusher,
    ): Response {
        $productId = (int)$request->get('product_id');
        $product = $products->get($productId);

        $dimension = $product->getDimensions();

        $form = $this->createForm(
            ProductDimensionForm::class,
            [
                'length' => $dimension->getLength(),
                'width' => $dimension->getWidth(),
                'height' => $dimension->getHeight(),
                'weight' => $dimension->getWeight(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->setDimensions(
                new ProductDimensions(
                    length: $data['length'],
                    width: $data['width'],
                    height: $data['height'],
                    weight: $data['weight']
                )
            );
            $flusher->flush();

            return $this->redirectToRoute('store.admin.product.dimension.edit', ['product_id'=> $productId]);
        }

        return $this->render(
            'admin/store/product/dimension/edit.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }
}
