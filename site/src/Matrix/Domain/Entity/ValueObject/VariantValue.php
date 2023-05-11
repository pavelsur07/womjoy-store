<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class VariantValue extends StringValueObject
{
    #[ORM\Column(type: 'string', length: 16)]
    protected $value;

    public function __construct($value)
    {
        parent::__construct(trim(mb_strtoupper($value)));
    }
}
