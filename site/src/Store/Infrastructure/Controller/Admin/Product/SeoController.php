<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product')]
class SeoController extends AbstractController
{
    #[Route('/{id}/seo/regenerate-seo-metadata', name: '.seo.regenerate')]
    public function delete(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher): Response
    {
        $product->regenerateSeoMetadataByTemplate();
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.seo', ['id'=> $product->getId()], Response::HTTP_SEE_OTHER);
    }
}
