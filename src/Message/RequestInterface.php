<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Psr\Http\Message\RequestInterface as HttpRequestInterface;

interface RequestInterface extends MessageInterface, HttpRequestInterface
{
    public const BATCH = 'BATCH';
    public const REQUEST = 'REQUEST';
    public const NOTIFICATION = 'NOTIFICATION';

    /**
     * Get rpc method
     */
    public function getRpcMethod(): ?string;

    /**
     * Get rpc parameters
     */
    public function getRpcParams(): ?array;
}
