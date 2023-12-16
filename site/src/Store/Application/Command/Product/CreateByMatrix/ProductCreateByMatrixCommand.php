<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\CreateByMatrix;

class ProductCreateByMatrixCommand
{
    public function __construct(
        private string $name,
        private readonly string $article,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getArticle(): string
    {
        return $this->article;
    }
}
