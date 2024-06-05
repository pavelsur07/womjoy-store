<?php

namespace App\Store\Domain\Entity\Cart\ValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class CartCustomer
{
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private null|string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private null|string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private null|string $address;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }
}