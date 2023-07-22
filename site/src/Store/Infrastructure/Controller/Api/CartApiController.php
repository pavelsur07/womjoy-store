<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Store\Domain\Entity\Cart\CartItem;
use App\Store\Infrastructure\Service\Cart\CartService;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/api/v1/cart', name: 'store.cart.api')]
class CartApiController extends AbstractController
{
    #[Route(path: '/', name: '.get', methods: ['GET'])]
    public function get(Request $request, CartService $service, UrlGeneratorInterface $generator, ThumbnailService $thumbnails): Response
    {
        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId($userId);
        $cart = $service->getCurrentCart();

        return $this->json(
            [
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'costDiscount' => $cart->getCostDiscount(),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => array_map(static function (CartItem $item) use ($generator, $thumbnails) {
                    return [
                        'id' => $item->getVariant()->getId(),
                        'href' => $generator->generate('store.product.show', ['slug' => $item->getVariant()->getProduct()->getSlug()]),
                        'name' => $item->getVariant()->getProduct()->getName(),
                        'quantity' => $item->getQuantity(),
                        'value' => $item->getVariant()->getValue(),
                        'price_old' =>$item->getVariant()->getProduct()->getPrice()->getPrice(),
                        'price_list' =>$item->getVariant()->getProduct()->getPrice()->getListPrice(),
                        'currency' => 'р.',
                        'thumbnail' => $thumbnails->generateUrl(
                            path: $item->getVariant()->getProduct()->getImages()->first()->getPath(),
                            file: $item->getVariant()->getProduct()->getImages()->first()->getName(),
                            width: 390,
                            height: 520,
                        ),
                    ];
                }, $cart->getItems()->toArray()),
            ]
        );
    }

    #[Route(path: '/quantity', name: '.quantity', methods: ['POST'])]
    public function quantity(Request $request, CartService $service, Flusher $flusher, UrlGeneratorInterface $generator, ThumbnailService $thumbnails): Response
    {
        $data = json_decode($request->getContent(), true);
        $variantId = (int)$data['productId']; // $request->get('variant_id');
        $quantity = (int)$data['quantity'];

        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId($userId);
        $cart = $service->getCurrentCart();
        try {
            $cart->setQuantity($variantId, $quantity);
            $flusher->flush();
            $message = 'success';
        } catch (DomainException $e) {
            $message = $e->getMessage();
        }

        return $this->json(
            [
                'message' =>$message,
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'costDiscount' => $cart->getCostDiscount(),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => array_map(static function (CartItem $item) use ($generator, $thumbnails) {
                    return [
                        'id' => $item->getVariant()->getId(),
                        'href' => $generator->generate('store.product.show', ['slug' => $item->getVariant()->getProduct()->getSlug()]),
                        'name' => $item->getVariant()->getProduct()->getName(),
                        'quantity' => $item->getQuantity(),
                        'value' => $item->getVariant()->getValue(),
                        'price_old' =>$item->getVariant()->getProduct()->getPrice()->getPrice(),
                        'price_list' =>$item->getVariant()->getProduct()->getPrice()->getListPrice(),
                        'currency' => 'р.',
                        'thumbnail' => $thumbnails->generateUrl(
                            path: $item->getVariant()->getProduct()->getImages()->first()->getPath(),
                            file: $item->getVariant()->getProduct()->getImages()->first()->getName(),
                            width: 390,
                            height: 520,
                        ),
                    ];
                }, $cart->getItems()->toArray()),
            ]
        );
    }

    #[Route(path: '/clear', name: '.clear', methods: ['POST'])]
    public function clear(Request $request, CartService $service, Flusher $flusher): Response
    {
        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId($userId);
        $cart = $service->getCurrentCart();
        $cart->clear();
        $flusher->flush();

        return $this->json(
            [
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'costDiscount' => $cart->getCostDiscount(),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => array_map(static function (CartItem $item): array {
                    return [
                        'id' => $item->getVariant()->getId(),
                        'href' => $this->generateUrl('store.product.show', ['slug'=> $item->getVariant()->getProduct()->getSlug()]),
                        'name' => $item->getVariant()->getProduct()->getName(),
                        'quantity' => $item->getQuantity(),
                        'value' => $item->getVariant()->getValue(),
                        'price_old' =>$item->getVariant()->getProduct()->getPrice()->getPrice(),
                        'price_list' =>$item->getVariant()->getProduct()->getPrice()->getListPrice(),
                        'currency' => 'р.',
                    ];
                }, $cart->getItems()->toArray()),
            ]
        );
    }
}
