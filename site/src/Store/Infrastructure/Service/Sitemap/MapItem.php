<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Sitemap;

use DateTimeImmutable;

class MapItem
{
    public const ALWAYS = 'always';
    public const HOURLY = 'hourly';
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';
    public const NEVER = 'never';

    public string $location;
    public DateTimeImmutable $lastModified;
    public string|null $changeFrequency;
    public $priority;

    public function __construct(string $location, DateTimeImmutable $lastModified = null, string|null $changeFrequency = null, $priority = null)
    {
        $this->location = $location;
        $this->lastModified = $lastModified;
        $this->changeFrequency = $changeFrequency;
        $this->priority = $priority;
    }
}
