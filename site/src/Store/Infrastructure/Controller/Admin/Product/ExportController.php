<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\Product\ProductExportEditForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{id}/export', name: 'store.admin.product.export')]
class ExportController extends AbstractController
{
    #[Route('/', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get((int)$request->get('id'));
        $form = $this->createForm(
            ProductExportEditForm::class,
            [
                'yandex' => $product->getExport()->isYandexMarket(),
                'sber' => $product->getExport()->isSberMarket(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $product->getExport()->setYandexMarket($data['yandex']);
                $product->getExport()->setSberMarket($data['sber']);
                $flusher->flush();
                $this->addFlash('success', 'Success export changed.');
            } catch (DomainException $e) {
                $this->addFlash('danger', 'Export export changed.');
            }
            return $this->redirectToRoute('store.admin.product.export.edit', ['id'=> $product->getId()]);
        }

        return $this->render(
            'admin/store/product/export/edit.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }
}
