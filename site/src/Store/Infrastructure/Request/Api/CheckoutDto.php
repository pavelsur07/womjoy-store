<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Request\Api;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutDto
{
    private const PAYMENT_TYPE_COD = 'cod';
    private const PAYMENT_TYPE_ONLINE = 'online';
    private const PAYMENT_TYPE_YANDEX_SPLIT = 'yandex_split';

    public function __construct(
        #[Assert\Valid]
        public readonly CheckoutCustomer $customer,
        #[Assert\Valid]
        public readonly CheckoutDelivery $delivery,
        #[Assert\Choice([self::PAYMENT_TYPE_COD, self::PAYMENT_TYPE_ONLINE, self::PAYMENT_TYPE_YANDEX_SPLIT])]
        public readonly string $payment
    ) {}
}
