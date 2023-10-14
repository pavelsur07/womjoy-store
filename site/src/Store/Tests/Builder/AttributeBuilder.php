<?php

declare(strict_types=1);

namespace App\Store\Tests\Builder;

use App\Store\Domain\Entity\Attribute\Attribute;

class AttributeBuilder
{
    public const ATTRIBUTE_NAME = 'attribute_name';
    public const ATTRIBUTE_TYPE = Attribute::TYPE_SINGLE_CHOICE;

    public function build(): Attribute
    {
        return new Attribute(name: self::ATTRIBUTE_NAME, type: self::ATTRIBUTE_TYPE);
    }
}
