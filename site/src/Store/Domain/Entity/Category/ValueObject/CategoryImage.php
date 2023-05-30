<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Category\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CategoryImage
{
    #[ORM\Column(type: Types::STRING, nullable: true, options: ['default'=> null])]
    private string|null $path;

    #[ORM\Column(type: Types::STRING, nullable: true, options: ['default'=> null])]
    private string|null $name;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['default'=> null])]
    private int|null $size;

    public function __construct(string $path = null, string $name = null, int $size = null)
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }

    public function update(string $path, string $name, int $size): void
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function isEmpty(): bool
    {
        return empty($this->name);
    }
}
