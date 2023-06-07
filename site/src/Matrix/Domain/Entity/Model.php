<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity;

use App\Matrix\Domain\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_models`')]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'model', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\Column(type: Types::STRING, length: 6, nullable: true)]
    private string|null $code = null;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->products = new ArrayCollection();
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

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getCode(): string|null
    {
        return $this->code;
    }

    public function setCode(string|null $code): void
    {
        $this->code = $code;
    }
}
