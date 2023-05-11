<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity;

use App\Matrix\Domain\Entity\ValueObject\ProductStatus;
use App\Matrix\Domain\Entity\ValueObject\VariantBarcode;
use App\Matrix\Domain\Entity\ValueObject\VariantValue;
use App\Matrix\Domain\Exception\MatrixException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`matrix_products`')]
#[ORM\UniqueConstraint(name: 'matrix_article_unique_index', columns: ['article'])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::STRING, unique: true)]
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

    /** @var ArrayCollection<array-key, Variant */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Variant::class, cascade: ['ALL'])]
    private Collection $variants;

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
        $this->variants = new ArrayCollection();
    }

    public function addVariant(VariantBarcode $barcode, VariantValue $value): void
    {
        foreach ($this->variants as $variant) {
            if ($variant->isEquivalentToBarcode($barcode)) {
                throw new MatrixException('Duplicate barcode value violates unique constraint.');
            }
        }
        $this->variants->add(new Variant(
            product: $this,
            barcode: $barcode,
            value: $value,
        ));
    }

    public function removeVariant(int $idVariant): void
    {
        foreach ($this->variants as $variant) {
            if ($variant->getId() === $idVariant) {
                $this->variants->removeElement($variant);
                return;
            }
        }

        throw new MatrixException('Variant not fount.');
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

    public function getVariants(): Collection
    {
        return $this->variants;
    }
}
