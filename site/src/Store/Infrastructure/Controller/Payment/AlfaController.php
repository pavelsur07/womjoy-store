<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Payment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart/checkout/pay/alfa', name: 'store.checkout.pay.alfa')]
class AlfaController extends AbstractController
{
}
