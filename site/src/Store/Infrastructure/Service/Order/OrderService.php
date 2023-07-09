<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Order;

use App\Auth\Infrastructure\Repository\UserRepository;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Cart\Cart;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderCustomer;
use App\Store\Domain\Entity\Order\ValueObject\OrderDelivery;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Infrastructure\Repository\OrderRepository;
use App\Store\Infrastructure\Request\Api\CheckoutDto;

final readonly class OrderService
{
    public function __construct(
        private UserRepository $customers,
        private OrderRepository $orders,
        private Flusher $flusher,
    ) {
    }

    public function checkout(Cart $cart, CheckoutDto $checkoutDto): Order
    {
        if ($cart->getCustomerId()) {
            $customerId = $this->customers->get($cart->getCustomerId())?->getId();
        } else {
            $customerId = null;
        }

        $customer = new OrderCustomer(
            phone: $checkoutDto->customer->phone,
            name: $checkoutDto->customer->name,
            email: $checkoutDto->customer->email,
            comment: $checkoutDto->customer->comment
        );

        $delivery = new OrderDelivery(
            address: $checkoutDto->delivery->address,
        );

        $payment = match ($checkoutDto->payment) {
            OrderPayment::PAYMENT_METHOD_COD => OrderPayment::cod(),
            OrderPayment::PAYMENT_METHOD_ONLINE => OrderPayment::online(
                OrderPayment::PAYMENT_STATUS_WAITING,
            ),
        };

        $order = new Order(
            customer: $customer,
            delivery: $delivery,
            payment: $payment,
            customerId: $customerId
        );

        foreach ($cart->getItems() as $item) {
            // checkout quantity
            $item->getVariant()->checkout(
                $item->getQuantity()
            );

            // add variant item to order and quantity
            $order->addItem(variant: $item->getVariant(), quantity: $item->getQuantity());
        }

        $this->orders->save($order);

        $this->flusher->flush();

        return $order;
    }
}
