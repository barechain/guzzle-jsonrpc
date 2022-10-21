<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Exception;

use Barechain\GuzzleHttp\JsonRpc\Message\{RequestInterface, ResponseInterface};
use GuzzleHttp\BodySummarizerInterface;
use GuzzleHttp\Exception\RequestException as HttpRequestException;
use Psr\Http\Message\{RequestInterface as HttpRequestInterface, ResponseInterface as HttpResponseInterface};
use Throwable;

class RequestException extends HttpRequestException
{
    /**
     * RequestException constructor
     */
    public static function create(
        HttpRequestInterface $request,
        ?HttpResponseInterface $response = null,
        ?Throwable $previous = null,
        ?array $handlerContext = null,
        BodySummarizerInterface $bodySummarizer = null
    ): HttpRequestException {
        if ($request instanceof RequestInterface && $response instanceof ResponseInterface) {
            static $clientErrorCodes = [-32600, -32601, -32602, -32700];

            $errorCode = $response->getRpcErrorCode();
            if (in_array($errorCode, $clientErrorCodes)) {
                $label = 'Client RPC error response';
                $className = ClientException::class;
            } else {
                $label = 'Server RPC error response';
                $className = ServerException::class;
            }

            $message = $label . ' [uri] ' . $request->getRequestTarget()
                . ' [method] ' . $request->getRpcMethod()
                . ' [error code] ' . $errorCode
                . ' [error message] ' . $response->getRpcErrorMessage();

            return new $className($message, $request, $response, $previous);
        }

        return parent::create($request, $response, $previous);
    }
}
