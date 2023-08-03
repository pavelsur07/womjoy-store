<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order\ValueObject;

use App\Common\Domain\Entity\ValueObject\IntegerValueObject;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderNumber extends IntegerValueObject
{
    #[ORM\Column(name: 'order_number', type: Types::INTEGER, nullable: true)]
    protected ?int $value = null;
}
