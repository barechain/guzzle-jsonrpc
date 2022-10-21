<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Psr\Http\Message\MessageInterface as HttpMessageInterface;

interface MessageInterface extends HttpMessageInterface
{
    /**
     * Get rpc id
     */
    public function getRpcId(): mixed;

    /**
     * Get rpc version
     */
    public function getRpcVersion(): ?string;
}
