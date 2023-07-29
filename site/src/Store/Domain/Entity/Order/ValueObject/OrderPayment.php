<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use App\Store\Domain\Exception\StoreOrderPaymentException;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(nullable: true)]
    private ?string $provider;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $transaction = [];

    public function __construct(string $method, ?string $status = null, ?string $provider = null)
    {
        $this->method = $method;
        $this->status = $status;
        $this->provider = $provider;
    }

    public static function cod(): self
    {
        return new static(self::PAYMENT_METHOD_COD);
    }

    public static function online(string $status, string $provider): self
    {
        return new static(self::PAYMENT_METHOD_ONLINE, $status, $provider);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getTransaction(): array
    {
        return $this->transaction;
    }

    public function setTransaction(array $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function setStatusSucceeded(): void
    {
        if ($this->status !== self::PAYMENT_STATUS_WAITING) {
            throw new StoreOrderPaymentException('Payment status is already.');
        }

        $this->status = self::PAYMENT_STATUS_SUCCEEDED;
    }

    public function setStatusCancelled(): void
    {
        if ($this->status !== self::PAYMENT_STATUS_WAITING) {
            throw new StoreOrderPaymentException('Payment status is already.');
        }

        $this->status = self::PAYMENT_STATUS_CANCELLED;
    }
}
