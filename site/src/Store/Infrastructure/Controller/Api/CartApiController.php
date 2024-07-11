<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Cart\CartItem;
use App\Store\Domain\Entity\Product\Image;
use App\Store\Infrastructure\Repository\VariantRepository;
use App\Store\Infrastructure\Request\Api\CartCustomer;
use App\Store\Infrastructure\Service\Cart\CartService;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/api/v1/cart', name: 'store.cart.api')]
class CartApiController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly UrlGeneratorInterface $generator,
        private readonly ThumbnailService $thumbnails
    ) {}

    #[Route(path: '/customer', name: '.customer', methods: ['GEt'])]
    public function customer(): Response
    {
        $cart = $this->cartService->getCurrentCart();

        return $this->json($cart->getCustomer());
    }

    #[Route(path: '/customer', name: '.customer_update', methods: ['POST'])]
    public function customerUpdate(#[MapRequestPayload] CartCustomer $cartCustomer, Flusher $flusher): Response
    {
        $cart = $this->cartService->getCurrentCart();

        // дополняем данные о покупателе
        $cart->getCustomer()->complement(
            $cartCustomer->name,
            $cartCustomer->email,
            $cartCustomer->phone,
        );

        $flusher->flush();

        return $this->json($cart->getCustomer());
    }

    #[Route(path: '/', name: '.get', methods: ['GET'])]
    public function get(): Response
    {
        $cart = $this->cartService->getCurrentCart();

        return $this->json(
            [
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'promoCodeDiscount' => $cart->getPromoCodeDiscount(),
                'costDiscount' => $cart->getCostDiscount(),
                'deliveryCost' => $cart->getDeliveryCost(
                    $cart->getCostDiscount(true)
                ),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => $this->getCartItems($cart),
            ]
        );
    }

    #[Route(path: '/add', name: '.add', methods: ['POST'])]
    public function add(Request $request, VariantRepository $variants, Flusher $flusher): Response
    {
        $data = $request->toArray();
        $variantId = (int)$data['productId'];
        $quantity = (int)$data['quantity'];

        $cart = $this->cartService->getCurrentCart();

        try {
            $cart->add(variant: $variants->get($variantId), quantity: $quantity);
            $flusher->flush();
            $message = 'success';
        } catch (DomainException $e) {
            $message = $e->getMessage();
        }

        return $this->json(
            [
                'message' => $message,
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'promoCodeDiscount' => $cart->getPromoCodeDiscount(),
                'costDiscount' => $cart->getCostDiscount(),
                'deliveryCost' => $cart->getDeliveryCost(
                    $cart->getCostDiscount(true)
                ),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => $this->getCartItems($cart),
            ]
        );
    }

    #[Route(path: '/quantity', name: '.quantity', methods: ['POST'])]
    public function quantity(Request $request, Flusher $flusher): Response
    {
        $data = $request->toArray();
        $variantId = (int)$data['productId'];
        $quantity = (int)$data['quantity'];

        $cart = $this->cartService->getCurrentCart();

        try {
            $cart->setQuantity($variantId, $quantity);
            $flusher->flush();

            $message = 'success';
        } catch (DomainException $e) {
            $message = $e->getMessage();
        }

        return $this->json(
            [
                'message' => $message,
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'promoCodeDiscount' => $cart->getPromoCodeDiscount(),
                'costDiscount' => $cart->getCostDiscount(),
                'deliveryCost' => $cart->getDeliveryCost(
                    $cart->getCostDiscount(true)
                ),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => $this->getCartItems($cart),
            ]
        );
    }

    #[Route(path: '/remove', name: '.remove', methods: ['POST'])]
    public function remove(Request $request, Flusher $flusher): Response
    {
        $data = $request->toArray();

        $cart = $this->cartService->getCurrentCart();
        $cart->remove($data['productId']);

        $flusher->flush();

        return $this->json(
            [
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'promoCodeDiscount' => $cart->getPromoCodeDiscount(),
                'costDiscount' => $cart->getCostDiscount(),
                'deliveryCost' => $cart->getDeliveryCost(
                    $cart->getCostDiscount(true)
                ),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => $this->getCartItems($cart),
            ]
        );
    }

    #[Route(path: '/clear', name: '.clear', methods: ['POST'])]
    public function clear(Flusher $flusher): Response
    {
        $cart = $this->cartService->getCurrentCart();
        $cart->clear();

        $flusher->flush();

        return $this->json(
            [
                'customer_id' => null,
                'cost' => $cart->getCost(),
                'promoCodeDiscount' => $cart->getPromoCodeDiscount(),
                'costDiscount' => $cart->getCostDiscount(),
                'deliveryCost' => $cart->getDeliveryCost(
                    $cart->getCostDiscount(true)
                ),
                'discount' => $cart->getDiscount(),
                'amount' => $cart->getAmount(),
                'items' => $this->getCartItems($cart),
            ]
        );
    }

    protected function transformCartItem(CartItem $item): array
    {
        /** @var Image|null $firstImage */
        $firstImage = $item->getVariant()->getProduct()?->getImages()->first();

        if ($firstImage) {
            $thumbnail = $this->thumbnails->generateUrl(
                path: $firstImage->getPath(),
                file: $firstImage->getName(),
                width: 300,
                height: 400,
            );
        } else {
            $thumbnail = '/pion/img/e404.svg';
        }

        return [
            'id' => $item->getVariant()->getId(),
            'product_id' => $item->getVariant()->getProduct()->getId(),
            'href' => $this->generator->generate(
                'store.product.show',
                [
                    'slug' => $item->getVariant()->getProduct()->getSlug(),
                ]
            ),
            'name' => $item->getVariant()->getProduct()->getName(),
            'article' => $item->getVariant()->getProduct()->getArticle(),
            'quantity' => $item->getQuantity(),
            'value' => $item->getVariant()->getValue(),
            'color_value' => $item->getVariant()->getProduct()->getColor(),
            'price_old' => $item->getVariant()->getProduct()->getPrice()->getPrice(),
            'price_list' => $item->getVariant()->getProduct()->getPrice()->getListPrice(),
            'currency' => '₽',
            'thumbnail' => $thumbnail,
        ];
    }

    protected function getCartItems(Cart $cart): array
    {
        return array_values(
            array_map([$this, 'transformCartItem'], $cart->getItems()->toArray())
        );
    }
}
