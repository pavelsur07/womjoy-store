<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Client;

use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\AuthorizeException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

use function json_encode;
use function json_last_error;
use function json_last_error_msg;

use const JSON_ERROR_NONE;

class GuzzleHttpClient implements ClientInterface
{
    private ?string $apikey;

    private array $defaultHeaders = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    private ?\Psr\Http\Client\ClientInterface $client;

    public function __construct(?\Psr\Http\Client\ClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @throws GuzzleException
     * @throws AuthorizeException
     * @throws ClientExceptionInterface
     */
    public function send(string $method, string $path, array $headers = [], array $data = []): ResponseInterface
    {
        $options = [
            RequestOptions::HEADERS => $this->prepareHeaders($headers),
        ];

        if (strtoupper($method) === 'POST') {
            $options[RequestOptions::JSON] = $data;
        }

        if ($this->client instanceof Client) {
            return $this->client->request($method, $path, $options);
        }

        $data = json_encode($data);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }

        $request = new Request($method, $path, $options[RequestOptions::HEADERS], $data);

        return $this->client->sendRequest($request);
    }

    public function setApikey(?string $apikey): self
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * @throws AuthorizeException
     */
    private function prepareHeaders(array $headers): array
    {
        $headers = array_merge($this->defaultHeaders, $headers);

        if ($this->apikey) {
            $headers['Authorization'] = 'Api-Key ' . $this->apikey;
        }

        if (empty($headers)) {
            throw new AuthorizeException('Authorization headers not set');
        }

        return $headers;
    }
}
