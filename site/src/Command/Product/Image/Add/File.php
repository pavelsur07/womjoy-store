<?php

namespace App\Command\Product\Image\Add;

final readonly class File
{
    public function __construct(
        private string $pach,
        private string $name,
        private string $size,
    )
    {
    }

    /**
     * @return string
     */
    public function getPatch(): string
    {
        return $this->pach;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }


}