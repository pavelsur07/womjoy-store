<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Seller;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_seller`')]
class Seller
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $inn;

    public function __construct(string $name, string $inn)
    {
        $this->name = $name;
        $this->inn = $inn;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function setInn(string $inn): void
    {
        $this->inn = $inn;
    }
}
