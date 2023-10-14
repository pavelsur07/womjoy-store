<?php

declare(strict_types=1);

namespace App\Store\Tests\Builder;

use App\Store\Domain\Entity\Attribute\Variant;

class AttributeVariantBuilder
{
    public const ATTRIBUTE_VARIANT_NAME = 'attribute_variant_name';

    public function build(): Variant
    {
        $attribute = (new AttributeBuilder())->build();
        return new Variant(attribute: $attribute, name: self::ATTRIBUTE_VARIANT_NAME);
    }
}
