<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductData
{
    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $article = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $barcode = null;

    public function __construct(string $name, ?string $article, ?string $barcode)
    {
        $this->name = $name;
        $this->article = $article;
        $this->barcode = $barcode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }
}
