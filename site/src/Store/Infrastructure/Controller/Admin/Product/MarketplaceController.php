<?php

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Form\Product\ProductMarketplaceEditForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

#[Route('/admin/product/{product_id}/marketplace', name: 'store.admin.product.marketplace')]
class MarketplaceController extends AbstractController
{
    #[Route('/edit', name: '.edit')]
    public function edit(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get((int)$request->get('product_id'));
        $form = $this->createForm(ProductMarketplaceEditForm::class,
            [
                'wildberriesUrl' => $product->getMarketplace()->getWb(),
                'ozonUrl'=> $product->getMarketplace()->getOzon(),
                'yandexUrl'=> $product->getMarketplace()->getYandex(),
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->getMarketplace()->setWb($data['wildberriesUrl']);
            $product->getMarketplace()->setOzon($data['ozonUrl']);
            $product->getMarketplace()->setYandex($data['yandexUrl']);
            $flusher->flush();
        }

        return $this->render('admin/product/marketplace/edit.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]);

    }
}