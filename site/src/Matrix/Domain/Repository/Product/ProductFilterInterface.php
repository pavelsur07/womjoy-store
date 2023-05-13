<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Repository\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;

interface ProductFilterInterface
{
    public function getColor(): ?Color;

    public function getSubject(): ?Subject;

    public function getModel(): ?Model;

    public function getArticle(): ?string;

    public function getName(): ?string;

    public function getStatus(): ?string;
}
