<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Order;

use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Cart\CartItem;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\CustomerData;
use App\Store\Infrastructure\Repository\OrderRepository;
use DateTimeImmutable;

final readonly class OrderService
{
    public function __construct(
        private UserRepository $customers,
        private OrderRepository $orders,
        private Flusher $flusher,
    ) {
    }

    public function checkout(int|null $customerId, Cart $cart): void
    {
        $user = $this->customers->get($customerId);

        $order = new Order(
            customerId: $user->getId(),
            createdAt: new DateTimeImmutable(),
            customerData: new CustomerData(
                phone: 'phone',
                name: 'name',
                email: 'email',
            )
        );

        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            $item->getVariant()->checkout($item->getQuantity());
            $order->addItem(variant: $item, quantity: $item->getQuantity());
        }

        $this->orders->save($order);
        $cart->clear();

        $this->flusher->flush();
    }
}
