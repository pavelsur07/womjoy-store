<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay;

use App\Store\Infrastructure\Service\Payment\YandexPay\Client\ClientInterface;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\AuthorizeException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\BadRequestException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Exception\ConflictException;
use App\Store\Infrastructure\Service\Payment\YandexPay\Request\CreateOrderRequest;
use App\Store\Infrastructure\Service\Payment\YandexPay\Request\CreateOrderResponse;
use App\Store\Infrastructure\Service\Payment\YandexPay\Request\OrderResponse;
use Exception;
use Psr\Http\Message\ResponseInterface;

readonly class YandexPay
{
    public const API_URI = 'https://pay.yandex.ru/api/merchant';
    public const API_URI_SANDBOX = 'https://sandbox.pay.yandex.ru/api/merchant';

    private string $baseUrl;

    public function __construct(
        private ClientInterface $client,
        private string $apikey,
        private bool $sandbox = true
    ) {
        // Set base url sandbox or prod base url
        $this->baseUrl = $this->sandbox ? self::API_URI_SANDBOX : self::API_URI;

        // Set API key for http client
        $this->client->setApikey($this->apikey);
    }

    /**
     * @throws AuthorizeException
     * @throws BadRequestException
     * @throws ConflictException
     */
    public function createOrder(CreateOrderRequest $order): ?CreateOrderResponse
    {
        $path = $this->prepareUrl('/v1/orders');

        $response = $this->client->send('post', $path, [], $order->toArray());

        if ($response->getStatusCode() !== 200) {
            $this->handleError($response);
        }

        $contents = @json_decode($response->getBody()->getContents(), true) ?? null;

        return new CreateOrderResponse(
            $contents['data'] ?? []
        );
    }

    /**
     * @throws AuthorizeException
     * @throws BadRequestException
     * @throws ConflictException
     */
    public function getOrder(string $orderId): ?OrderResponse
    {
        $path = $this->prepareUrl('/v1/orders/' . $orderId);

        $response = $this->client->send('get', $path);

        if ($response->getStatusCode() !== 200) {
            $this->handleError($response);
        }

        $contents = @json_decode($response->getBody()->getContents(), true) ?? null;

        return new OrderResponse(
            $contents['data'] ?? []
        );
    }

    /**
     * @throws Exception
     */
    public function cancelOrder(string $orderId): void
    {
        throw new Exception('The method is not implemented');
    }

    /**
     * @throws Exception
     */
    public function refundOrder(string $orderId): void
    {
        throw new Exception('The method is not implemented');
    }

    private function prepareUrl(string $path): string
    {
        return $this->baseUrl . $path;
    }

    /**
     * @throws BadRequestException
     * @throws AuthorizeException
     * @throws ConflictException
     */
    private function handleError(ResponseInterface $response): void
    {
        $contents = $response->getBody()->getContents();
        $statusCode = $response->getStatusCode();

        throw match ($statusCode) {
            AuthorizeException::HTTP_CODE => new AuthorizeException($contents),
            BadRequestException::HTTP_CODE => new BadRequestException($contents),
            ConflictException::HTTP_CODE => new ConflictException($contents),
            default => new Exception($response->getReasonPhrase()),
        };
    }
}
