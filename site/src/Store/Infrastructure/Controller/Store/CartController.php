<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\VariantRepository;
use App\Store\Infrastructure\Service\Cart\CartService;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart', name: 'store.cart')]
class CartController extends BaseController
{
    #[Route(path: '/', name: '.index')]
    public function cart(Request $request, CartService $service): Response
    {
        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId();

        return $this->render(
            'store/cart/cart.html.twig',
            [
                'user' => $userId,
                'cart' => $service->getCurrentCart(customerId: $userId),
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }

    #[Route(path: '/add', name: '.add', methods: ['POST'])]
    public function add(Request $request, CartService $service, VariantRepository $variants, Flusher $flusher): Response
    {
        $data = json_decode($request->getContent(), true);
        $variantId = (int)$data['variant_id']; // $request->get('variant_id');
        $quantity = (int)$data['quantity'];

        $userId = null;
        $user = $this->getUser();
        $userId = $user?->getId();

        $cart = $service->getCurrentCart(customerId: $userId);
        try {
            $cart->add(variant: $variants->get($variantId), quantity: $quantity);
            $flusher->flush();
            $message = 'success';
        } catch (DomainException $e) {
            $message = $e->getMessage();
        }

        return $this->json([
            'message' => $message,
            'amount' => $cart->getAmount(),
            'variant_id'=>$variantId,
            'items' => [],
        ]);
    }

    #[Route(path: '/quantity', name: '.quantity', methods: ['POST'])]
    public function quantity(): Response
    {
        $product = null;
        $quantity = null;
        return $this->json([]);
    }

    #[Route(path: '/remove', name: '.remove')]
    public function remove(): Response
    {
        $product = null;
        return $this->json([]);
    }

    #[Route(path: '/clear', name: '.clear')]
    public function clear(): Response
    {
        return $this->json([]);
    }

    /*    public function deserializerCart(Cart $cart): array
        {
            return [
                'amount' => $cart->getAmount(),
                'subtotal' => $cart->getSubtotal(),
                'items' => array_map(
                    static function (CartItem $item): array {
                        return [
                            'href' => '',
                            'image' =>'',
                            'name' => $item->getVariant()->getProduct()->getName(),
                            'variantValue' =>$item->getVariant()->getValue(),
                            'priceOld' => $item->getVariant()->getProduct()->getPrice()->getPrice(),
                            'priceList' => $item->getPrice(),
                            'quantity' => $item->getQuantity(),
                            'cost' => $item->getCost(),
                        ];
                    },
                    $cart->getItems()->toArray()
                ),
            ];
        }*/
}
