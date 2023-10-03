<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{product_id}', name: 'store.admin.product.status')]
class StatusController extends AbstractController
{
    #[Route('/active', name: '.active', methods: ['GET', 'POST'])]
    public function active(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get((int)$request->get('product_id'));
        $product->active();

        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.edit', ['id' => $product->getId()]);
    }

    #[Route(path: '/hide', name: '.hide')]
    public function hide(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get((int)$request->get('product_id'));
        $product->getStatus()->hide();
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.edit', ['id' => $product->getId()]);
    }
}
