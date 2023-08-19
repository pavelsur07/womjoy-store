<?php

declare(strict_types=1);

namespace App\ImportProduct\Infrastructure\Wildberries\Exception;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class WildberriesApiException extends Exception
{
    private RequestInterface $request;
    private ResponseInterface $response;

    public function __construct(string $message, RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($message);
    }

    final public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    final public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
