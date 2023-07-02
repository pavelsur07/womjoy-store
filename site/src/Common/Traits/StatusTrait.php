<?php

declare(strict_types=1);

namespace App\Common\Traits;

trait StatusTrait
{
    public const DRAFT = 'draft';
    public const ACTIVE = 'active';
    public const DISABLE = 'disable';

    public const HIDE = 'hide';

    public static function list(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::DISABLE,
        ];
    }
}
