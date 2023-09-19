<?php

declare(strict_types=1);

namespace App\Common\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssertOneOf extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('assert_one_of', [$this, 'oneOf'], ['is_safe' => ['html']]),
        ];
    }

    public function oneOf(mixed $value, array $values): bool
    {
        if (!\in_array($value, $values, true)) {
            return false;
        }
        return true;
    }
}
