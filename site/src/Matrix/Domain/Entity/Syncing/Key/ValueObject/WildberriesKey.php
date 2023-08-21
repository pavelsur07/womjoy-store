<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\Syncing\Key\ValueObject;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
class WildberriesKey
{
    public const KEY_STATISTICS = 'wb_statistics';

    #[Column(type: 'string', nullable: true)]
    private string|null $value = null;

    #[Column(type: 'string', nullable: true)]
    private string|null $type = null;

    public function __construct(string $value = null, string $type = null)
    {
        Assert::oneOf($type, self::list());
        $this->value = trim($value);
        $this->type = $type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function list(): array
    {
        return [
            self::KEY_STATISTICS,
        ];
    }
}
