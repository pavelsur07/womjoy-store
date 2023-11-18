<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class OrderCustomer
{
    #[ORM\Column(nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'guid', nullable: true)]
    private ?string $userId = null;

    public function __construct(
        string $phone,
        string $name,
        string $email,
        ?string $comment,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $userId = null
    ) {
        Assert::email($email);

        $this->phone = $phone;
        $this->name = $name;
        $this->email = $email;
        $this->comment = $comment;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        if ($userId !== null) {
            Assert::uuid($userId);
            $this->userId = $userId;
        }
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }
}
