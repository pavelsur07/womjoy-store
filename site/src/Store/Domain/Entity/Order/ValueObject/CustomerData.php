<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CustomerData
{
    #[ORM\Column]
    private string $phone;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $email;

    public function __construct(string $phone, string $name, string $email)
    {
        $this->phone = $phone;
        $this->name = $name;
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
