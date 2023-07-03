<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller;

use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Cart\CartItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class DeserializerCart
{
    public function __construct(
        private FileUploader $uploader,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function deserialize(Cart $cart): array
    {
        return [
            'amount' => $cart->getAmount(),
            'subtotal' => $cart->getSubtotal(),
            'items' => array_map(
                static function (CartItem $item): array {
                    return [
                        'href' => $this->urlGenerator->generate(
                            'store.product.show',
                            $item->getVariant()->getProduct()->getSlug()
                        ),
                        'image' => $this->uploader->generateUrl(
                            path: $item->getVariant()->getProduct()->getImages()->first()->getPath() .
                            '/' . $item->getVariant()->getProduct()->getImages()->first()->getName()
                        ),
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
    }
}
