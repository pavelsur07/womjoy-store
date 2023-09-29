<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Home;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Domain\Exception\StoreHomeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`store_home_pages`')]
class Home
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private int $id;
    #[ORM\Embedded(class: SeoMetadata::class, columnPrefix: false)]
    private SeoMetadata $seoMetadata;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActiveNewProduct = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActiveBestseller = false;

    /** @var ArrayCollection<AssignCategory> */
    #[ORM\OneToMany(mappedBy: 'home', targetEntity: AssignCategory::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $categories;

    #[ORM\Column(type: 'text', options: ['default' => ''])]
    private string $seoText = '';

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isActiveSeoText = false;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $hrefNewProduct = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $hrefBestseller = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function assignCategory(Category $category): void
    {
        foreach ($this->categories as $item) {
            if ($item->getCategory()->getId() === $category->getId()) {
                throw new StoreHomeException('Assign already category.');
            }
        }

        $this->categories->add(new AssignCategory(home: $this, category: $category));
    }

    public function revokeCategory(int $id): void
    {
        foreach ($this->categories as $item) {
            if ($item->getCategory()->getId() === $id) {
                $this->categories->removeElement($item);
                return;
            }
        }
        throw new StoreHomeException('Category not found.');
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setSeoMetadata(SeoMetadata $seoMetadata): void
    {
        $this->seoMetadata = $seoMetadata;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSeoMetadata(): SeoMetadata
    {
        return $this->seoMetadata;
    }

    public function isActiveNewProduct(): bool
    {
        return $this->isActiveNewProduct;
    }

    public function isActiveBestseller(): bool
    {
        return $this->isActiveBestseller;
    }

    public function getSeoText(): string
    {
        return $this->seoText;
    }

    public function setSeoText(string $seoText): void
    {
        $this->seoText = $seoText;
    }

    public function isActiveSeoText(): bool
    {
        return $this->isActiveSeoText;
    }

    public function setIsActiveSeoText(bool $isActiveSeoText): void
    {
        $this->isActiveSeoText = $isActiveSeoText;
    }

    public function getHrefNewArrivals(): ?string
    {
        return $this->hrefNewProduct;
    }

    public function setHrefNewArrivals(?string $hrefNewProduct): void
    {
        $this->hrefNewProduct = $hrefNewProduct;
    }

    public function getHrefBestseller(): ?string
    {
        return $this->hrefBestseller;
    }

    public function setHrefBestseller(?string $hrefBestseller): void
    {
        $this->hrefBestseller = $hrefBestseller;
    }

    public function setIsActiveNewArrivals(bool $isActiveNewProduct): void
    {
        $this->isActiveNewProduct = $isActiveNewProduct;
    }

    public function setIsActiveBestseller(bool $isActiveBestseller): void
    {
        $this->isActiveBestseller = $isActiveBestseller;
    }
}
