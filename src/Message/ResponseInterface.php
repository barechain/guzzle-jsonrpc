<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface extends MessageInterface, HttpResponseInterface
{
    /**
     * Get rpc error code
     */
    public function getRpcErrorCode(): ?int;

    /**
     * Get rpc error message
     */
    public function getRpcErrorMessage(): ?string;

    /**
     * Get rpc error data
     */
    public function getRpcErrorData(): mixed;

    /**
     * Get rpc result
     */
    public function getRpcResult(): mixed;
}
