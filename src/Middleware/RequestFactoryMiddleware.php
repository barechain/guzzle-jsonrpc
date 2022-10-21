<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Message\MessageFactoryInterface;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;

class RequestFactoryMiddleware extends AbstractMiddleware
{
    protected MessageFactoryInterface $factory;

    /**
     * RequestFactoryMiddleware constructor
     */
    public function __construct(MessageFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Apply request
     */
    public function applyRequest(HttpRequestInterface $request, array $options): HttpRequestInterface
    {
        return $this->factory->fromRequest($request);
    }
}