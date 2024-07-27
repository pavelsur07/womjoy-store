<?php

declare(strict_types=1);

namespace App\Banner\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`banner_banners_items`')]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Banner::class, inversedBy: 'items')]
    private Banner $banner;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $patchDesktopImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $patchMobileImage = null;

    public function __construct(string $id, Banner $banner)
    {
        Assert::uuid($id);
        $this->id = $id;
        $this->banner = $banner;
    }

    public function getPatchDesktopImage(): ?string
    {
        return $this->patchDesktopImage;
    }

    public function setPatchDesktopImage(?string $patchDesktopImage): void
    {
        $this->patchDesktopImage = $patchDesktopImage;
    }

    public function getPatchMobileImage(): ?string
    {
        return $this->patchMobileImage;
    }

    public function setPatchMobileImage(?string $patchMobileImage): void
    {
        $this->patchMobileImage = $patchMobileImage;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }
}
