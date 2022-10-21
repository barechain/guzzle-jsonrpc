<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Exception;

use InvalidArgumentException;
use Throwable;

class JsonDecodeException extends InvalidArgumentException
{
    private string $json;

    /**
     * JsonDecodeException constructor
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, string $json = '')
    {
        parent::__construct($message, $code, $previous);
        $this->json = $json;
    }

    /**
     * Get json
     */
    public function getJson(): string
    {
        return $this->json;
    }
}
