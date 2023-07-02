<?php

declare(strict_types=1);

namespace App\Page\Domain\Entity\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use App\Common\Traits\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class PageStatus extends StringValueObject
{
    use StatusTrait;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::list());
        parent::__construct();
    }
}
