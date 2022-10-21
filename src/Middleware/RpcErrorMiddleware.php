<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Exception\RequestException;
use Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use Psr\Http\Message\{RequestInterface as HttpRequestInterface, ResponseInterface as HttpResponseInterface};

class RpcErrorMiddleware extends AbstractMiddleware
{
    /**
     * Apply response
     *
     * @throws RequestException
     */
    public function applyResponse(
        HttpRequestInterface $request,
        HttpResponseInterface $response,
        array $options
    ): HttpResponseInterface {

        if ($response instanceof ResponseInterface &&
            isset($options['rpc_error']) &&
            true === $options['rpc_error'] &&
            null !== $response->getRpcErrorCode()
        ) {
            throw RequestException::create($request, $response);
        }

        return $response;
    }
}
