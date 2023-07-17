<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine;

use App\Common\Domain\Entity\ValueObject\UuidValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

/** @psalm-suppress all */
abstract class UuidType extends GuidType
{
    final public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $className = $this->typeClassName();

        if ($value) {
            return new $className($value);
        }

        return $value;
    }

    final public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof UuidValueObject) {
            return $value->value();
        }

        return $value;
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    abstract protected function typeClassName(): string;
}
