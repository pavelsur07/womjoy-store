<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\True_;

#[ORM\Embeddable]
class SeoMetadata
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $h1 = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $seoTitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private string|null $seoDescription = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => true])]
    private bool|null $isIndexOn = false;

    public function __construct(?string $h1 = null, ?string $title = null, ?string $description = null, bool $isIndexOn = false)
    {
        $this->h1 = $h1;
        $this->seoTitle = $title;
        $this->seoDescription = $description;
        $this->isIndexOn = $isIndexOn;
    }

    public function getH1(): ?string
    {
        return $this->h1;
    }

    public function setH1(?string $h1): void
    {
        $this->h1 = $h1;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    public function isIndexOn(): bool
    {
        return $this->isIndexOn;
    }

    public function setIsIndexOn(bool $isIndexOn): void
    {
        $this->isIndexOn = $isIndexOn;
    }
}
