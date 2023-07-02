<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingSeoDefault
{
    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $h1;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $title;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    private string|null $description;

    public function __construct(?string $h1 = '', ?string $title = '', ?string $description = '')
    {
        $this->h1 = $h1;
        $this->title = $title;
        $this->description = $description;
    }

    public function getH1(): ?string
    {
        return $this->h1;
    }

    public function setH1(?string $h1): void
    {
        $this->h1 = $h1;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
