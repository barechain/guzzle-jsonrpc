<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc;
use Barechain\GuzzleHttp\JsonRpc\Message\{MessageFactory, MessageFactoryInterface, RequestInterface, ResponseInterface};
use Barechain\GuzzleHttp\JsonRpc\Middleware\{
    RequestFactoryMiddleware,
    RequestHeaderMiddleware,
    ResponseFactoryMiddleware,
    RpcErrorMiddleware
};
use GuzzleHttp\{Client as HttpClient, ClientInterface as HttpClientInterface};
use GuzzleHttp\Promise\PromiseInterface;

class Client implements ClientInterface
{
    protected HttpClientInterface $httpClient;
    protected MessageFactoryInterface $messageFactory;

    /**
     * Client constructor
     */
    public function __construct(HttpClientInterface $httpClient, MessageFactoryInterface $factory)
    {
        $this->httpClient = $httpClient;
        $this->messageFactory = $factory;

        $handler = $this->httpClient->getConfig('handler'); //todo: deprecated
        $handler->push(new RequestFactoryMiddleware($factory));
        $handler->push(new RequestHeaderMiddleware());
        $handler->push(new RpcErrorMiddleware());
        $handler->push(new ResponseFactoryMiddleware($factory));
    }

    /**
     * Factory method
     */
    public static function factory(string $uri, array $config = []): Client
    {
        if (isset($config['message_factory'])) {
            $factory = $config['message_factory'];
            unset($config['message_factory']);
        } else {
            $factory = new MessageFactory();
        }

        return new self(new HttpClient(array_merge($config, [
            'base_uri' => $uri,
        ])), $factory);
    }

    /**
     * Notification
     */
    public function notification(string $method, ?array $params = null): RequestInterface
    {
        return $this->createRequest(
            RequestInterface::NOTIFICATION,
            array_filter([
                'jsonrpc' => self::SPEC,
                'method' => $method,
                'params' => $params,
            ])
        );
    }

    /**
     * Request
     */
    public function request(mixed $id, string $method, ?array $params = null): RequestInterface
    {
        return $this->createRequest(RequestInterface::REQUEST, array_filter([
            'jsonrpc' => self::SPEC,
            'method' => $method,
            'params' => $params,
            'id' => $id,
        ]));
    }

    /**
     * Send
     */
    public function send(RequestInterface $request): ?ResponseInterface
    {
        $promise = $this->sendAsync($request);

        return $promise->wait();
    }

    /**
     * Send async request
     */
    public function sendAsync(RequestInterface $request): PromiseInterface
    {
        return $this->httpClient->sendAsync($request)->then(
            function (ResponseInterface $response) use ($request) {
                return $request->getRpcId() ? $response : null;
            }
        );
    }

    /**
     * Send all requests
     *
     * @param  RequestInterface[]  $requests
     * @return ResponseInterface[]
     */
    public function sendAll(array $requests): array
    {
        $promise = $this->sendAllAsync($requests);

        return $promise->wait();
    }

    /**
     * Send all async
     *
     * @param  RequestInterface[]  $requests
     */
    public function sendAllAsync(array $requests): PromiseInterface
    {
        return $this->httpClient->sendAsync($this->createRequest(
            RequestInterface::BATCH,
            $this->getBatchRequestOptions($requests)
        ))->then(function (ResponseInterface $response) {
            return $this->getBatchResponses($response);
        });
    }

    /**
     * Create request
     */
    protected function createRequest(string $method, array $options): RequestInterface
    {
        $uri = $this->httpClient->getConfig('base_uri'); //todo: deprecated
        $defaults = $this->httpClient->getConfig('defaults'); //todo: deprecated
        $headers = $defaults['headers'] ?? [];

        return $this->messageFactory->createRequest($method, $uri, $headers, $options);
    }

    /**
     * Get batch request options
     *
     * @param  RequestInterface[] $requests
     */
    protected function getBatchRequestOptions(array $requests): array
    {
        return array_map(function (RequestInterface $request) {
            return JsonRpc\json_decode((string) $request->getBody());
        }, $requests);
    }

    /**
     * Get batch responses
     *
     * @return ResponseInterface[]
     */
    protected function getBatchResponses(ResponseInterface $response): array
    {
        $results = JsonRpc\json_decode((string) $response->getBody(), true);

        return array_map(function (array $result) use ($response) {
            return $this->messageFactory->createResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $result
            );
        }, $results);
    }
}
