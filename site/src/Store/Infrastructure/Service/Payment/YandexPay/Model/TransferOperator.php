<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

class TransferOperator extends AbstractObject
{
    /**
     * Max length: 2048.
     */
    private ?string $address = null;

    /**
     * Max length: 2048.
     */
    private ?string $inn = null;

    /**
     * Max length: 2048.
     */
    private ?string $name = null;

    /**
     * Max length: 2048.
     */
    private ?array $phones = null;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(?string $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): self
    {
        $this->phones = $phones;

        return $this;
    }
}
