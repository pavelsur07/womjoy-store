<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Repository\Product\ProductFilterInterface;

class ProductFilter implements ProductFilterInterface
{
    public ?string $name = null;
    public ?string $article = null;
    public ?string $status = null;
    public Subject|null $subject = null;
    public Color|null $color = null;
    public Model|null $model = null;

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
