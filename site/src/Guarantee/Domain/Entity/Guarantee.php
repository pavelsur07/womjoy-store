<?php

declare(strict_types=1);

namespace App\Guarantee\Domain\Entity;

use App\Guarantee\Domain\Entity\ValueObject\GuaranteeService;
use App\Guarantee\Domain\Entity\ValueObject\GuaranteeStatus;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`guarantee_guarantees`')]
class Guarantee
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable|null $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable|null $updatedAt = null;

    #[ORM\Column]
    private string $phone;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $message;

    #[ORM\Embedded(class: GuaranteeService::class, columnPrefix: 'service_')]
    private GuaranteeService $serviceName;

    #[ORM\Embedded(class: GuaranteeStatus::class, columnPrefix: 'status_')]
    private GuaranteeStatus $status;

    /** @var ArrayCollection<Image> */
    #[ORM\OneToMany(mappedBy: 'guarantee', targetEntity: Image::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $images;

    public function __construct(string $phone, string $email, string $message, GuaranteeService $serviceName, DateTimeImmutable $createdAt)
    {
        Assert::email($email);
        $this->phone = $phone;
        $this->email = $email;
        $this->message = $message;
        $this->serviceName = $serviceName;
        $this->status = new GuaranteeStatus(GuaranteeStatus::NEW);
        $this->images = new ArrayCollection();
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getServiceName(): GuaranteeService
    {
        return $this->serviceName;
    }

    public function setServiceName(GuaranteeService $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    public function getStatus(): GuaranteeStatus
    {
        return $this->status;
    }

    public function setStatus(GuaranteeStatus $status): void
    {
        $this->status = $status;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImages(Collection $images): void
    {
        $this->images = $images;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
