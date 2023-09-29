<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment;

use InvalidArgumentException;

class PaymentProvider
{
    public const PAYMENT_PROVIDER_ALFA = 'alfa';
    public const PAYMENT_PROVIDER_STRIPE = 'stripe';
    public const PAYMENT_PROVIDER_INTELLECT_MONEY = 'intellectmoney';

    private const PAYMENT_PROVIDER_ROUTES = [
        //        UNSUPPORTED
        //        self::PAYMENT_PROVIDER_INTELLECT_MONEY => 'store.checkout.payment.intellectmoney',

        self::PAYMENT_PROVIDER_ALFA => 'store.checkout.payment.alfa.purchase',
        self::PAYMENT_PROVIDER_STRIPE => 'store.checkout.payment.stripe.purchase',
    ];

    public function __construct(
        private readonly string $providerName,
    ) {}

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    public function getControllerRoute(): string
    {
        if (self::PAYMENT_PROVIDER_ROUTES[$this->providerName] ?? null) {
            return self::PAYMENT_PROVIDER_ROUTES[$this->providerName];
        }

        throw new InvalidArgumentException(
            sprintf('The "%s" provider is not supported', $this->providerName)
        );
    }
}
