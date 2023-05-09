<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity;

use App\Matrix\Domain\Entity\ValueObject\ProductStatus;
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

    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: 'products')]
    private Model $model;

    #[ORM\ManyToOne(targetEntity: Color::class, inversedBy: 'products')]
    private Color $color;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Embedded(class: ProductStatus::class, columnPrefix: 'status_')]
    private ProductStatus $status;

    public function __construct(
        DateTimeImmutable $createdAt,
        string $article,
        string $name,
        Subject $subject,
        Model $model,
        Color $color,
    ) {
        $this->createdAt = $createdAt;
        $this->article = $article;
        $this->subject = $subject;
        $this->model = $model;
        $this->color = $color;
        $this->name = $name;
        $this->status = new ProductStatus(ProductStatus::DRAFT);
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

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function getStatus(): ProductStatus
    {
        return $this->status;
    }
}
