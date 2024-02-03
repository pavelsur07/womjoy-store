<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

use Evgeek\Moysklad\MoySklad;

readonly class MoyskladClient
{
    private MoySklad $client;

    public function __construct(MoyskladCredentials $credentials)
    {
        $this->client = new MoySklad(
            $credentials->get()
        );
    }

    public function get(): MoySklad
    {
        return $this->client;
    }
}
