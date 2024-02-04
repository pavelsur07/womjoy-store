<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Store\Domain\Entity\Product\SubscribeProduct;
use App\Store\Domain\Entity\Product\ValueObject\SubscribeProductId;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Repository\SubscribeProductRepository;
use App\Store\Infrastructure\Repository\VariantRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product.subscriber')]
class SubscriberController extends AbstractController
{
    #[Route(path: '/{id}/subscriber', name: '.index')]
    public function index(int $id, Request $request, ProductRepository $products, SubscribeProductRepository $subscribes): Response
    {
        $product = $products->get($id);
        $variantIds = [];

        foreach ($product->getVariants() as $variant) {
            $variantIds[] = $variant->getId();
        }

        return $this->render(
            'admin/store/product/subscriber/index.html.twig',
            [
                'product' => $product,
                'variants' => $subscribes->listByVariantIds($variantIds),
            ]
        );
    }

    #[Route(path: '/{id}/subscriber/{variantId}/add-test', name: '.add_test')]
    public function addTest(int $id, Request $request, VariantRepository $variants, SubscribeProductRepository $subscribes): Response
    {
        $variantId = (int)$request->get('variantId');
        $variant = $variants->get($variantId);

        $subscribe = new SubscribeProduct(
            id: SubscribeProductId::generate(),
            variant: $variant,
            email: 'test@app.ru',
            createdAt: new DateTimeImmutable()
        );

        if ($subscribes->hasItem($subscribe)) {
            $this->addFlash('danger', 'Already subscribe - ' . $variantId);
            return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
        }

        $subscribes->save($subscribe, true);

        $this->addFlash('success', 'Subscribe success - ' . $variantId);
        return $this->redirectToRoute('store.admin.product.edit', ['id'=> $id]);
    }
}
