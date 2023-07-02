<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity;

use App\Setting\Domain\Entity\ValueObject\SettingCompany;
use App\Setting\Domain\Entity\ValueObject\SettingSeoDefault;
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
    private string|null $phone = null;

    #[ORM\Column(nullable: true)]
    private string|null $email = null;

    #[ORM\Embedded(class: SettingSeoDefault::class, columnPrefix: 'seo_')]
    private SettingSeoDefault $seoDefault;

    #[ORM\Embedded(class: SettingCompany::class, columnPrefix: 'company_')]
    private SettingCompany $company;

    public function __construct()
    {
        $this->company = new SettingCompany();
        $this->seoDefault = new SettingSeoDefault();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSeoDefault(): SettingSeoDefault
    {
        return $this->seoDefault;
    }

    public function getCompany(): SettingCompany
    {
        return $this->company;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
