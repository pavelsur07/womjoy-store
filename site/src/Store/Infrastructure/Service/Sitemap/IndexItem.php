<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Sitemap;

class IndexItem
{
    public $location;
    public $lastModified;

    public function __construct($location, $lastModified = null)
    {
        $this->location = $location;
        $this->lastModified = $lastModified;
    }
}
