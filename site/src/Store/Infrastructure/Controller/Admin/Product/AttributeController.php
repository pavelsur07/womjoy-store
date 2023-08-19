<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{id}/attributes', name: 'store.admin.product.attribute')]
class AttributeController extends AbstractController
{
    #[Route('/', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $product = $products->get((int)$request->get('id'));

        return $this->render(
            'admin/store/product/attribute/edit.html.twig',
            [
                'product'=> $product,
            ]
        );
    }
}
