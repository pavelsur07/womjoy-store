<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Wildberries;

use App\ImportProduct\Infrastructure\Wildberries\Exception\AccessDeniedException;
use App\ImportProduct\Infrastructure\Wildberries\Exception\InternalServerErrorException;
use App\ImportProduct\Infrastructure\Wildberries\Exception\InvalidParameterException;
use App\ImportProduct\Infrastructure\Wildberries\Exception\RequestConflictException;
use App\ImportProduct\Infrastructure\Wildberries\Exception\ResponseNotFoundException;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpRequest
{
    private GuzzleClient $httpClient;

    public function __construct(string $baseUri, string $accessToken, array $options = [])
    {
        $defaultOptions = [
            'base_uri' => $baseUri,
            'headers' => [
                'Authorization' => $accessToken,
                'Content-Type' => 'application/json',
            ],
        ];

        /**
         * @psalm-suppress  RedundantCondition
         */
        if ($options) {
            $defaultOptions = array_merge_recursive($defaultOptions, $options);
        }

        $this->httpClient = new GuzzleClient(
            $defaultOptions
        );
    }

    /**
     * @throws AccessDeniedException
     * @throws ResponseNotFoundException
     * @throws RequestConflictException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     */
    public function get(string $url, array $data = [], array $options = []): array
    {
        /* $options = array_merge($options, [
             'query' => $data,
         ]);*/

        return $this->parseResponse(
            $this->send('GET', $url, $options)
        );
    }

    /**
     * @throws AccessDeniedException
     * @throws ResponseNotFoundException
     * @throws RequestConflictException
     * @throws InvalidParameterException
     * @throws InternalServerErrorException
     */
    public function post(string $url, array $data = [], array $options = []): array
    {
        $options = array_merge($options, [
            'json' => $data,
        ]);

        return $this->parseResponse(
            $this->send('POST', $url, $options)
        );
    }

    protected function decodeContent(string $content): array
    {
        $decoded = json_decode($content, true);

        if (!\is_array($decoded)) {
            $decoded = [];
        }

        return $decoded;
    }

    /**
     * @throws AccessDeniedException
     * @throws ResponseNotFoundException
     * @throws RequestConflictException
     * @throws InternalServerErrorException
     * @throws InvalidParameterException
     * @throws Exception
     */
    private function send(string $method, string $uri, array $options): ResponseInterface
    {
        try {
            return $this->httpClient->request($method, $uri, $options);
        } catch (ClientException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();

            $code = $response->getStatusCode();
            $message = $response->getReasonPhrase();

            if ($code === 400) {
                throw new InvalidParameterException($message, $request, $response);
            }

            if ($code === 403) {
                throw new AccessDeniedException($message, $request, $response);
            }

            if ($code === 404) {
                throw new ResponseNotFoundException($message, $request, $response);
            }

            if ($code === 409) {
                throw new RequestConflictException($message, $request, $response);
            }

            throw new InternalServerErrorException(
                sprintf('Service responded with error code: "%s" and message: "%s"', $code, $message),
                $request,
                $response
            );
        } catch (GuzzleException $exception) {
            throw new Exception(
                sprintf('Service responded with error message: "%s"', $exception->getMessage())
            );
        }
    }

    private function parseResponse(ResponseInterface $response): array
    {
        return $this->decodeContent(
            $response->getBody()->getContents()
        );
    }
}
