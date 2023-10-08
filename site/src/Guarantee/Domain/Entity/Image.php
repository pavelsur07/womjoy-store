<?php

declare(strict_types=1);

namespace App\Guarantee\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private Guarantee $guarantee;

    #[ORM\Column]
    private string $path;

    #[ORM\Column]
    private string $name;

    public function __construct(Guarantee $guarantee, string $path, string $name)
    {
        $this->guarantee = $guarantee;
        $this->path = $path;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGuarantee(): Guarantee
    {
        return $this->guarantee;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
