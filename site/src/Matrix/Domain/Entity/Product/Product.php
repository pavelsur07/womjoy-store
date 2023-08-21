<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Product;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Entity\Subject;
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

    #[ORM\Column(type: Types::STRING, unique: true, nullable: true)]
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

    /** @var ArrayCollection<array-key, Variant> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Variant::class, cascade: ['ALL'])]
    private Collection $variants;

    /** @var ArrayCollection<array-key, Image> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    private Collection $images;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $pathExternalImage = null;

    /** @var ArrayCollection<array-key, Cost> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Cost::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $costs;

    /** @var ArrayCollection<array-key, Event> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Event::class, cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $events;

    /** @var ArrayCollection<array-key, ProductIdentity> */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductIdentity::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $identifiers;

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
        $this->images = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->identifiers = new ArrayCollection();
    }

    // Identity

    public function getIdentifiers(): Collection
    {
        return $this->identifiers;
    }

    // Event

    public function getEvents(): Collection
    {
        return $this->events;
    }

    // Cost
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    // Image
    public function imageUp(int $sortNumber): void
    {
        if ($sortNumber === 0) {
            throw new MatrixException('Error sort number 0.');
        }

        $up = $sortNumber;
        $down = $sortNumber-1;

        $imageUp = $this->findImageBySortNumber($up);
        $imageDown = $this->findImageBySortNumber($down);

        $imageUp->setSort($up-1);
        $imageDown->setSort($down+1);
    }

    public function imageDown(int $sortNumber): void
    {
        $count = $this->images->count()-1;
        if ($sortNumber === $count) {
            throw new MatrixException('Error sort number max.' . $count);
        }

        $down = $sortNumber;
        $up = $sortNumber+1;

        $imageDown = $this->findImageBySortNumber($down);
        $imageUp = $this->findImageBySortNumber($up);

        $imageDown->setSort($down+1);
        $imageUp->setSort($up-1);
    }

    public function findImageBySortNumber(int $sortNumber): Image
    {
        foreach ($this->images as $image) {
            if ($image->getSort() === $sortNumber) {
                return $image;
            }
        }

        throw new MatrixException('Image not found this sort number ' . $sortNumber);
    }

    public function addImage(string $path, string $fileName, int $size): void
    {
        $count = \count($this->images);
        $this->images->add(
            new Image(
                product: $this,
                sort: $count++,
                path: $path,
                fileName: $fileName,
                size: $size,
            )
        );
    }

    public function removeImage(int $imageId): void
    {
        $this->images->first();

        foreach ($this->images as $image) {
            if ($image->isEqualToId($imageId)) {
                $this->images->removeElement($image);
                $this->sortable();
                return;
            }
        }

        throw new MatrixException('Image not found.');
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

    public function getVariant(int $variantId): Variant
    {
        foreach ($this->variants as $variant) {
            if ($variant->getId() === $variantId) {
                return $variant;
            }
        }

        throw new MatrixException('Variant not fount.');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setSubject(Subject $subject): void
    {
        $this->subject = $subject;
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function setColor(Color $color): void
    {
        $this->color = $color;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): void
    {
        /*
        if (!$this->status->isDraft()) {
            throw new MatrixException('Product status is not draft.');
        }
        */
        $this->article = $article;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
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

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getImage(int $imageId): Image
    {
        foreach ($this->images as $image) {
            if ($image->isEqualToId($imageId)) {
                return $image;
            }
        }

        throw new MatrixException('Image not found');
    }

    public function getPathExternalImage(): ?string
    {
        return $this->pathExternalImage;
    }

    public function setPathExternalImage(?string $pathExternalImage): void
    {
        $this->pathExternalImage = $pathExternalImage;
    }

    private function sortable(): void
    {
        $this->images->first();
        $number = 0;
        foreach ($this->images as $image) {
            $image->setSort($number);
            ++$number;
        }
    }
}
