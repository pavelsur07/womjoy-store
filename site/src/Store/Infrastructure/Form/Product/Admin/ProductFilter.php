<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Form\Product\Admin;

class ProductFilter
{
    public ?string $name = null;

    public ?string $article = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }
}
