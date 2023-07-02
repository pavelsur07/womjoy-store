<?php

declare(strict_types=1);

namespace App\Common\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait DateTrait
{
    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $updatedAt;
    #[ORM\Column(type: 'date_immutable')]
    private ?DateTimeImmutable $publishedAt;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setPublishedAt(?DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }
}
