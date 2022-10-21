<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Psr\Http\Message\RequestInterface as HttpRequestInterface;

class RequestHeaderMiddleware extends AbstractMiddleware
{
    /**
     * Apply request
     */
    public function applyRequest(HttpRequestInterface $request, array $options): HttpRequestInterface
    {
        return $request
            ->withHeader('Accept-Encoding', 'gzip;q=1.0,deflate;q=0.6,identity;q=0.3')
            ->withHeader('Content-Type', 'application/json');
    }
}
