<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Closure;
use Psr\Http\Message\{RequestInterface as HttpRequestInterface, ResponseInterface as HttpResponseInterface};

abstract class AbstractMiddleware
{
    /**
     * Invoke function
     */
    public function __invoke(callable $fn): Closure
    {
        return function (HttpRequestInterface $request, array $options) use ($fn) {
            return $fn(call_user_func([$this, 'applyRequest'], $request, $options), $options)->then(
                function (HttpResponseInterface $response) use ($request, $options) {
                    return call_user_func([$this, 'applyResponse'], $request, $response, $options);
                }
            );
        };
    }

    /**
     * Apply request
     */
    public function applyRequest(HttpRequestInterface $request, array $options): HttpRequestInterface
    {
        return $request;
    }

    /**
     * Apply response
     */
    public function applyResponse(
        HttpRequestInterface $request,
        HttpResponseInterface $response,
        array $options
    ): HttpResponseInterface {
        return $response;
    }
}
