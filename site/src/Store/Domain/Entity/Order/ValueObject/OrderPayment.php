<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderPayment
{
    public const PAYMENT_METHOD_COD = 'cod';
    public const PAYMENT_METHOD_ONLINE = 'online';

    public const PAYMENT_STATUS_WAITING = 'waiting';
    public const PAYMENT_STATUS_SUCCEEDED = 'succeeded';
    public const PAYMENT_STATUS_CANCELLED = 'cancelled';

    #[ORM\Column]
    private string $method;
    #[ORM\Column(nullable: true)]
    private ?string $status;

    public function __construct(string $method, ?string $status = null)
    {
        $this->method = $method;
        $this->status = $status;
    }

    public static function cod(): self
    {
        return new static(self::PAYMENT_METHOD_COD);
    }

    public static function online(string $status): self
    {
        return new static(self::PAYMENT_METHOD_ONLINE, $status);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
