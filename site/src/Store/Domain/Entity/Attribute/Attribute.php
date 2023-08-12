<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Attribute;

use App\Store\Domain\Exception\StoreAttributeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`store_attributes`')]
class Attribute
{
    public const TYPE_SINGLE_CHOICE = 'single_choice';
    public const TYPE_MULTI_CHOICE = 'multi_choice';
    public const TYPE_CUSTOMER_VALUE = 'customer_value';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $type;

    /** @var ArrayCollection<array-key, Variant> */
    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Variant::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $variants;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default'=> false])]
    private bool $isBrand = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default'=> false])]
    private bool $isColor = false;

    public function __construct(string $name, string $type)
    {
        Assert::oneOf($type, self::getTypeList());
        $this->name = $name;
        $this->type = $type;
        $this->variants = new ArrayCollection();
    }

    public function addVariant(string $variantName): void
    {
        $this->variants->add(
            new Variant(
                attribute: $this,
                name: strip_tags(
                    trim($variantName)
                )
            )
        );
    }

    public function isBrandActive(bool $isBrand): void
    {
        if ($this->isColor()) {
            throw new StoreAttributeException('Error this attributes is Brand.');
        }

        if ($this->isBrand()) {
            throw new StoreAttributeException('Error already active brand.');
        }

        $this->isBrand = true;
    }

    public function isColorActive(bool $isColor): void
    {
        if ($this->isColor()) {
            throw new StoreAttributeException('Error already active Color.');
        }

        if ($this->isBrand()) {
            throw new StoreAttributeException('Error this attributes is Brand.');
        }

        $this->isColor = true;
    }


    public function editName(string $name): void
    {
        $this->name = $name;
    }

    public function isBrand(): bool
    {
        return $this->isBrand;
    }

    public function isColor(): bool
    {
        return $this->isColor;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public static function getTypeList(): array
    {
        return [
            self::TYPE_CUSTOMER_VALUE,
            self::TYPE_MULTI_CHOICE,
            self::TYPE_SINGLE_CHOICE,
        ];
    }

    public function removeVariant(int $variantId): void
    {
        foreach ($this->variants as $variant) {
            if ($variantId === $variant->getId()) {
                $this->variants->removeElement($variant);
                return;
            }
        }

        throw new StoreAttributeException('Variant not found');
    }
}
