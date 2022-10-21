<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Message\{RequestInterface, ResponseInterface};
use GuzzleHttp\Promise\PromiseInterface;

interface ClientInterface
{
    public const SPEC = '2.0';

    /**
     * Build a notification request object
     */
    public function notification(string $method, array $params = null): RequestInterface;

    /**
     * Build a request object
     */
    public function request(mixed $id, string $method, ?array $params = null): RequestInterface;

    /**
     * Send a request
     */
    public function send(RequestInterface $request): ? ResponseInterface;

    /**
     * Send a request asynchronously
     */
    public function sendAsync(RequestInterface $request): PromiseInterface;

    /**
     * Send a batch of requests
     *
     * @return ResponseInterface[]
     */
    public function sendAll(array $requests): array;

    /**
     * Send an asynchronous batch of requests
     */
    public function sendAllAsync(array $requests): PromiseInterface;
}
