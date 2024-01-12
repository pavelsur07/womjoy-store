<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingSeoDefault
{
    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $h1;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $title;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    private null|string $description;

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
