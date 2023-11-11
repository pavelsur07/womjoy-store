<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\AmoCRM;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_amo_crm_access_token`')]
class AmoCRMoAccessToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $clientId = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $baseDomain = null;

    private ?string $accessToken = null;
    private ?string $refreshToken = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $expires = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getBaseDomain(): ?string
    {
        return $this->baseDomain;
    }

    public function getExpires(): ?DateTimeImmutable
    {
        return $this->expires;
    }
}
