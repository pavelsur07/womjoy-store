<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity;

use App\Setting\Domain\Entity\ValueObject\SettingCompany;
use App\Setting\Domain\Entity\ValueObject\SettingMoysklad;
use App\Setting\Domain\Entity\ValueObject\SettingSeoDefault;
use App\Setting\Domain\Entity\ValueObject\SettingUnisender;
use App\Setting\Domain\Entity\ValueObject\SettingYandexMetrika;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`setting_setting`')]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $email = null;

    #[ORM\Embedded(class: SettingSeoDefault::class, columnPrefix: 'seo_')]
    private SettingSeoDefault $seoDefault;

    #[ORM\Embedded(class: SettingCompany::class, columnPrefix: 'company_')]
    private SettingCompany $company;

    #[ORM\Column(nullable: true)]
    private ?string $storeName = null;

    #[ORM\Column(nullable: true)]
    private ?string $storeUrl = null;

    #[ORM\Embedded(class: SettingUnisender::class, columnPrefix: 'unisender_')]
    private SettingUnisender $unisender;

    #[ORM\Embedded(class: SettingMoysklad::class, columnPrefix: 'moysklad_')]
    private SettingMoysklad $moysklad;

    #[ORM\Embedded(class: SettingYandexMetrika::class,columnPrefix: 'yandex_metrika_')]
    private SettingYandexMetrika $yandexMetrika;

    public function __construct()
    {
        $this->company = new SettingCompany();
        $this->seoDefault = new SettingSeoDefault();
    }

    public function getYandexMetrika(): SettingYandexMetrika
    {
        return $this->yandexMetrika;
    }

    public function setYandexMetrika(SettingYandexMetrika $yandexMetrika): void
    {
        $this->yandexMetrika = $yandexMetrika;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMoysklad(): SettingMoysklad
    {
        return $this->moysklad;
    }

    public function getUnisender(): SettingUnisender
    {
        return $this->unisender;
    }

    public function setUnisender(SettingUnisender $unisender): void
    {
        $this->unisender = $unisender;
    }

    public function getSeoDefault(): SettingSeoDefault
    {
        return $this->seoDefault;
    }

    public function getCompany(): SettingCompany
    {
        return $this->company;
    }

    public function getStoreName(): ?string
    {
        return $this->storeName;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setMoysklad(SettingMoysklad $moysklad): void
    {
        $this->moysklad = $moysklad;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setStoreName(?string $storeName): void
    {
        $this->storeName = $storeName;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getStoreUrl(): ?string
    {
        return $this->storeUrl;
    }

    public function setStoreUrl(?string $storeUrl): void
    {
        $this->storeUrl = $storeUrl;
    }
}
