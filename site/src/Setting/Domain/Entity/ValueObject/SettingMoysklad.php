<?php

declare(strict_types=1);

namespace App\Setting\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SettingMoysklad
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $companyId = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $skladId = null;

    public function __construct(?string $token, ?string $companyId, ?string $skladId)
    {
        $this->token = $token;
        $this->companyId = $companyId;
        $this->skladId = $skladId;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function getSkladId(): ?string
    {
        return $this->skladId;
    }
}
