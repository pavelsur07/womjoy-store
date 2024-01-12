<?php

declare(strict_types=1);

namespace App\Page\Domain\Entity;

use App\Common\Traits\DateTrait;
use App\Common\Traits\SeoMetadataTrait;
use App\Page\Domain\Entity\ValueObject\PageStatus;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`page_pages`')]
class Page
{
    use DateTrait;
    use SeoMetadataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Embedded(class: PageStatus::class, columnPrefix: 'status_')]
    private PageStatus $status;
    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $value = '';

    #[ORM\Column(type: 'string', nullable: true)]
    private null|string $slug = null;

    public function __construct(
        string $name,
        DateTimeImmutable $createdAt,
    ) {
        $this->name = $name;
        $this->status = new PageStatus(PageStatus::DRAFT);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($createdAt);
        $this->setPublishedAt($createdAt);
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeValue(string $value): void
    {
        $this->value = $value;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): PageStatus
    {
        return $this->status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable());
    }
}
