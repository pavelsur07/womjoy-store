<?php

declare(strict_types=1);

namespace App\Guarantee\Domain\Entity\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use App\Common\Infrastructure\Helper\ExternalService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class GuaranteeService extends StringValueObject
{
    #[ORM\Column(type: Types::STRING, length: 20)]
    protected $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, ExternalService::list());
        parent::__construct($value);
    }
}
