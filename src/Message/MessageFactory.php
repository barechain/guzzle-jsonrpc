<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc;
use Psr\Http\Message\{
    RequestInterface as HttpRequestInterface,
    ResponseInterface as HttpResponseInterface,
    UriInterface
};

class MessageFactory implements MessageFactoryInterface
{
    /**
     * Create request
     */
    public function createRequest(
        string $method,
        string|UriInterface $uri,
        array $headers = [],
        array $options = []
    ): RequestInterface {

        $body = JsonRpc\json_encode($this->addIdToRequest($method, $options));

        return new Request('POST', $uri, $headers, $body === false ? null : $body);
    }

    /**
     * Create response
     */
    public function createResponse(int $statusCode, array $headers = [], array $options = []): ResponseInterface
    {
        $body = JsonRpc\json_encode($options);

        return new Response($statusCode, $headers, $body === false ? null : $body);
    }

    /**
     * Get from request
     */
    public function fromRequest(HttpRequestInterface $request): RequestInterface
    {
        return $this->createRequest(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            JsonRpc\json_decode((string) $request->getBody(), true) ?: []
        );
    }

    /**
     * Get from response
     */
    public function fromResponse(HttpResponseInterface $response): ResponseInterface
    {
        return $this->createResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            JsonRpc\json_decode((string) $response->getBody(), true) ?: []
        );
    }

    /**
     * Add id to request
     */
    protected function addIdToRequest(string $method, array $data): array
    {
        if (RequestInterface::REQUEST === $method && ! isset($data['id'])) {
            $data['id'] = uniqid(more_entropy: true);
        }

        return $data;
    }
}
