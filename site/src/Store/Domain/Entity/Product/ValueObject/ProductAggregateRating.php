<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Product\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductAggregateRating
{
    #[ORM\Column(type: 'integer', options: ['default'=>0])]
    private int $reviewCount = 0;

    #[ORM\Column(type: 'float', options: ['default'=>0])]
    private float $ratingValue = 0;

    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    public function setRatingValue(float $ratingValue): void
    {
        $this->ratingValue = $ratingValue;
    }

    public function recalculation(): void
    {
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    public function getRatingValue(): float
    {
        return $this->ratingValue;
    }
}
