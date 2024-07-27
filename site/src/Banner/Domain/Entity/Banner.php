<?php

declare(strict_types=1);

namespace App\Banner\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`banner_banners`')]
class Banner
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 16)]
    private string $status;

    /** @var ArrayCollection<Item, array-key> */
    #[ORM\OneToMany(mappedBy: 'banner', targetEntity: Item::class, cascade: ['ALL'], orphanRemoval: true)]
    private ArrayCollection $items;

    public function __construct(string $id, string $name)
    {
        Assert::uuid($id);
        $this->id = $id;
        $this->name = $name;
        $this->status = self::INACTIVE;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
