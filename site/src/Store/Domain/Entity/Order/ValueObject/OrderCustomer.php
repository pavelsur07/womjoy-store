<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderCustomer
{
    #[ORM\Column(nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?string $comment = null;

    public function __construct(string $phone, string $name, string $email, ?string $comment = null)
    {
        $this->phone = $phone;
        $this->name = $name;
        $this->email = $email;
        $this->comment = $comment;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
