<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Cart;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

class Cart
{
    private int $id;
    private string $customerId;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private string $status;
    private Collection $items;

    /**
     * @param string            $customerId
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(string $customerId, DateTimeImmutable $createdAt)
    {
        $this->customerId = $customerId;
        $this->createdAt = $createdAt;
    }

}
