<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingCompany
{
    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $name = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $postalCode = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $addressCountry = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $addressLocality = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $streetAddress = null;

    public function __construct(
        ?string $name='',
        ?string $postalCode='',
        ?string $addressCountry='',
        ?string $addressLocality='',
        ?string $streetAddress='',
    ) {
        $this->name = $name;
        $this->postalCode = $postalCode;
        $this->addressCountry = $addressCountry;
        $this->addressLocality = $addressLocality;
        $this->streetAddress = $streetAddress;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getAddressCountry(): ?string
    {
        return $this->addressCountry;
    }

    public function setAddressCountry(?string $addressCountry): void
    {
        $this->addressCountry = $addressCountry;
    }

    public function getAddressLocality(): ?string
    {
        return $this->addressLocality;
    }

    public function setAddressLocality(?string $addressLocality): void
    {
        $this->addressLocality = $addressLocality;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress): void
    {
        $this->streetAddress = $streetAddress;
    }
}
