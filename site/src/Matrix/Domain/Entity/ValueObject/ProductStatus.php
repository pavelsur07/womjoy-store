<?php

declare(strict_types=1);

namespace App\Matrix\Domain\Entity\ValueObject;

use App\Common\Domain\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class ProductStatus extends StringValueObject
{
    public const DRAFT = 'draft';
    public const ACTIVE = 'active';
    public const MODEL_DESIGN = 'model_design';
    public const DEVELOPMENT = 'development';
    public const DEVELOPED = 'developed';
    public const READY_TO_SALE = 'ready_to_output';
    // Ready to output
    public const INITIAL_SALE = 'initial_sale';
    public const SALE = 'sale';
    // Withdrawal
    public const ARCHIVED = 'archived';

    #[ORM\Column(type: 'string', length: 16, options: ['default'=> self::DRAFT])]
    protected $value;

    public function __construct($value)
    {
        Assert::oneOf($value, self::list());
        $this->value = $value;
        parent::__construct($value);
    }

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::ARCHIVED,
        ];
    }
}
