<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\AmoCRM;

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
    private ?string $IntegrationId = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $SecretKey = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $baseDomain = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $accessToken = null;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $refreshToken = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $expires = null;

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

    public function getExpires(): ?int
    {
        return $this->expires;
    }

    public function getIntegrationId(): ?string
    {
        return $this->IntegrationId;
    }

    public function getSecretKey(): ?string
    {
        return $this->SecretKey;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setBaseDomain(?string $baseDomain): void
    {
        $this->baseDomain = $baseDomain;
    }

    public function setIntegrationId(?string $IntegrationId): void
    {
        $this->IntegrationId = $IntegrationId;
    }

    public function setSecretKey(?string $SecretKey): void
    {
        $this->SecretKey = $SecretKey;
    }

    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function setExpires(?int $expires): void
    {
        $this->expires = $expires;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
