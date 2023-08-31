<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Finance\ValueObject;

class SaleCost
{
    private ?int $product = null;
    private ?int $logistic = null;
    private ?int $commissionMarket = null;
    private ?int $commissionPay = null;
}
