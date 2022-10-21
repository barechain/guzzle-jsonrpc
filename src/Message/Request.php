<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc;
use GuzzleHttp\Psr7\Request as HttpRequest;

class Request extends HttpRequest implements RequestInterface
{
    /**
     * Get rpc id
     */
    public function getRpcId(): mixed
    {
        return $this->getFieldFromBody('id');
    }

    /**
     * Get rpc method
     */
    public function getRpcMethod(): ?string
    {
        return $this->getFieldFromBody('method');
    }

    /**
     * Get rpc params
     */
    public function getRpcParams(): ?array
    {
        return $this->getFieldFromBody('params');
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
