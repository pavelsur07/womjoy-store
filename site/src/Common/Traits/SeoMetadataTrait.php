<?php

declare(strict_types=1);

namespace App\Common\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait SeoMetadataTrait
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private null|string $h1 = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private null|string $seoTitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private null|string $seoDescription = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['default' => true])]
    private null|bool $isIndexOn = false;

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
