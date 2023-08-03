<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart/checkout/payment/intellectmoney', name: 'store.checkout.payment.intellectmoney')]
class IntellectmoneyController extends AbstractController
{
    #[Route('/{orderId}', name: '.purchase')]
    public function purchase(string $orderId): Response
    {
        throw new LogicException('Unsupported payment method');
    }

    #[Route('/{orderId}/success', name: '.success')]
    public function success(string $orderId): void
    {
        throw new LogicException('Unsupported payment method');
    }

    #[Route('/{orderId}/fail', name: '.fail')]
    public function fail(string $orderId): void
    {
        throw new LogicException('Unsupported payment method');
    }
}
