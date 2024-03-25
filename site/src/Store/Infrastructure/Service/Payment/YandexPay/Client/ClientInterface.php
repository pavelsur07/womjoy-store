<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Client;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function send(string $method, string $path, array $headers = [], array $data = []): ResponseInterface;

    public function setApikey(?string $apikey): mixed;
}
