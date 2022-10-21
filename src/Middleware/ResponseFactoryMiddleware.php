<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Message\MessageFactoryInterface;
use Psr\Http\Message\{RequestInterface as HttpRequestInterface, ResponseInterface as HttpResponseInterface};

class ResponseFactoryMiddleware extends AbstractMiddleware
{
    protected MessageFactoryInterface $factory;

    /**
     * ResponseFactoryMiddleware constructor
     */
    public function __construct(MessageFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Apply response
     */
    public function applyResponse(
        HttpRequestInterface $request,
        HttpResponseInterface $response,
        array $options
    ): HttpResponseInterface {

        return $this->factory->fromResponse($response);
    }
}
