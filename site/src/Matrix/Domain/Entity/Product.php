<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_products`')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::STRING)]
    private string $article;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: 'products')]
    private Subject $subject;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public function __construct(DateTimeImmutable $createdAt, string $article, Subject $subject, string $name)
    {
        $this->createdAt = $createdAt;
        $this->article = $article;
        $this->subject = $subject;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
