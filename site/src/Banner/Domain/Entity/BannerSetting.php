<?php

declare(strict_types=1);

namespace App\Banner\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
#[ORM\Table(name: '`banner_setting`')]
class BannerSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ManyToOne(targetEntity: Banner::class)]
    private ?Banner $heroSlider = null;

    public function __construct(?Banner $heroSlider = null)
    {
        $this->heroSlider = $heroSlider;
    }

    public function getHeroSlider(): ?Banner
    {
        return $this->heroSlider;
    }

    public function setHeroSlider(?Banner $heroSlider): void
    {
        $this->heroSlider = $heroSlider;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
