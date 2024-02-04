<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product;

use App\Store\Domain\Entity\Product\ValueObject\SubscribeProductId;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'store_product_subscribes')]
class SubscribeProduct
{
    #[ORM\Column(type: 'store_product_subscribe_uuid')]
    #[ORM\Id]
    private SubscribeProductId $id;
    #[ORM\ManyToOne(targetEntity: Variant::class)]
    private Variant $variant;

    #[ORM\Column(type: 'string', length: 60)]
    private string $email;

    public function __construct(SubscribeProductId $id, Variant $variant, string $email)
    {
        Assert::email($email);
        $this->id = $id;
        $this->variant = $variant;
        $this->email = $email;
    }

    public function getId(): SubscribeProductId
    {
        return $this->id;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
