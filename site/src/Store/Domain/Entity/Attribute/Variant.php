<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Attribute;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_attribute_variants`')]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Attribute::class, inversedBy: 'variants')]
    private Attribute $attribute;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string $customerValue;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $colorValue = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;

    public function __construct(Attribute $attribute, string $name, ?string $colorValue = null)
    {
        $this->attribute = $attribute;
        $this->name = $name;
        $this->colorValue = $colorValue;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function active(): void
    {
        $this->isActive = true;
    }


    public function disable(): void
    {
        $this->isActive = false;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }


    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCustomerValue(): string
    {
        return $this->customerValue;
    }

    public function setCustomerValue(string $customerValue): void
    {
        $this->customerValue = $customerValue;
    }

    public function getColorValue(): ?string
    {
        return $this->colorValue;
    }

    public function setColorValue(?string $colorValue): void
    {
        $this->colorValue = $colorValue;
    }
}
