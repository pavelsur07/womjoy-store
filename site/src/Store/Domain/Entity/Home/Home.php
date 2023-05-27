<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home;

use App\Store\Domain\Entity\SeoMetadata;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_home_pages`')]
class Home
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\Embedded(class: SeoMetadata::class, columnPrefix: false)]
    private SeoMetadata $seoMetadata;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActiveNewProduct = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActiveBestseller = false;

    public function setSeoMetadata(SeoMetadata $seoMetadata): void
    {
        $this->seoMetadata = $seoMetadata;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSeoMetadata(): SeoMetadata
    {
        return $this->seoMetadata;
    }

    public function isActiveNewProduct(): bool
    {
        return $this->isActiveNewProduct;
    }

    public function isActiveBestseller(): bool
    {
        return $this->isActiveBestseller;
    }
}
