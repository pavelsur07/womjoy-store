<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Product\ValueObject\ProductGarmentCare;
use App\Store\Infrastructure\Form\Product\ProductGarmentCareForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{product_id}/garment-care', name: 'store.admin.product.garment_care')]
class GarmentCareController extends AbstractController
{
    #[Route('/', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ProductRepository $products,
        FileUploader $uploader,
        Flusher $flusher,
    ): Response {
        $product = $products->get((int)$request->get('product_id'));

        $form = $this->createForm(
            ProductGarmentCareForm::class,
            [
                'text' => $product->getGarmentCare()->value(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $product->setGarmentCare(new ProductGarmentCare($data['text']));
                $flusher->flush();
                $this->addFlash('success', 'Success export changed.');
            } catch (DomainException $e) {
                $this->addFlash('danger', 'Export export changed.');
            }

            return $this->redirectToRoute('store.admin.product.garment_care.edit', ['product_id'=> $product->getId()]);
        }

        return $this->render(
            'admin/store/product/garment_care/edit.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }
}
