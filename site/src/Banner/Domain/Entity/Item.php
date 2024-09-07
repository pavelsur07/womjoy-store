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
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Banner::class, inversedBy: 'items')]
    private Banner $banner;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $patchDesktopImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $nameDesktopImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $patchMobileImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $nameMobileImage = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['default' => false])]
    private bool $isShortBanner;

    public function __construct(string $id, Banner $banner)
    {
        Assert::uuid($id);
        $this->id = $id;
        $this->banner = $banner;
    }

    public function isShortBanner(): bool
    {
        return $this->isShortBanner;
    }

    public function setIsShortBanner(bool $isShortBanner): void
    {
        $this->isShortBanner = $isShortBanner;
    }


    public function getNameDesktopImage(): ?string
    {
        return $this->nameDesktopImage;
    }

    public function setNameDesktopImage(?string $nameDesktopImage): void
    {
        $this->nameDesktopImage = $nameDesktopImage;
    }

    public function getNameMobileImage(): ?string
    {
        return $this->nameMobileImage;
    }

    public function setNameMobileImage(?string $nameMobileImage): void
    {
        $this->nameMobileImage = $nameMobileImage;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
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
