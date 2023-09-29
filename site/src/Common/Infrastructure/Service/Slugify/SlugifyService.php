<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Slugify;

use Cocur\Slugify\Slugify;

final readonly class SlugifyService
{
    public function __construct(
        private Slugify $slugify
    ) {}

    public function generate(string $slug): string
    {
        return $this->slugify->slugify($slug);
    }
}
