<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Psr\Http\Message\{
    RequestInterface as HttpRequestInterface,
    ResponseInterface as HttpResponseInterface,
    UriInterface
};

interface MessageFactoryInterface
{
    /**
     * Create request
     */
    public function createRequest(
        string $method,
        string|UriInterface $uri,
        array $headers = [],
        array $options = []
    ): RequestInterface;

    /**
     * Create response
     */
    public function createResponse(int $statusCode, array $headers = [], array $options = []): ResponseInterface;

    /**
     * From request
     */
    public function fromRequest(HttpRequestInterface $request): RequestInterface;

    /**
     * From response
     */
    public function fromResponse(HttpResponseInterface $response): ResponseInterface;
}
