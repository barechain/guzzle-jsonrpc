<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc;
use GuzzleHttp\Psr7\Response as HttpResponse;

class Response extends HttpResponse implements ResponseInterface
{
    /**
     * Get rpc error code
     */
    public function getRpcErrorCode(): ?int
    {
        $error = $this->getFieldFromBody('error');

        return $error['code'] ?? null;
    }

    /**
     * Get rpc error message
     */
    public function getRpcErrorMessage(): ?string
    {
        $error = $this->getFieldFromBody('error');

        return $error['message'] ?? null;
    }

    /**
     * Get rpc error data
     */
    public function getRpcErrorData(): mixed
    {
        $error = $this->getFieldFromBody('error');

        return $error['data'] ?? null;
    }

    /**
     * Get rpc id
     */
    public function getRpcId(): mixed
    {
        return $this->getFieldFromBody('id');
    }

    /**
     * Get rpc result
     */
    public function getRpcResult(): mixed
    {
        return $this->getFieldFromBody('result');
    }

    /**
     * Get rpc version
     */
    public function getRpcVersion(): ?string
    {
        return $this->getFieldFromBody('jsonrpc');
    }

    /**
     * Get field from body
     */
    protected function getFieldFromBody(string $key): mixed
    {
        $rpc = JsonRpc\json_decode((string) $this->getBody(), true);

        return $rpc[$key] ?? null;
    }
}
