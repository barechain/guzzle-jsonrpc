<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Exception\JsonDecodeException;

/**
 * Wrapper for JSON decode that implements error detection with helpful error messages
 */
function json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0): mixed
{
    static $jsonErrors = [
         JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
         JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
         JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
         JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
         JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded',
    ];

    // Patched support for decoding empty strings for PHP 7+
    $data = \json_decode($json == "" ? "{}" : $json, $assoc, $depth, $options);

    if (JSON_ERROR_NONE !== json_last_error()) {
        $last = json_last_error();
        $message = 'Unable to parse JSON data: ' . ($jsonErrors[$last] ?? 'Unknown error');

        throw new JsonDecodeException($message, 0, null, $json);
    }

    return $data;
}

/**
 * Wrapper for json_encode that includes character escaping by default
 */
function json_encode(mixed $data, bool $escapeChars = true): string|bool
{
    $options =
        JSON_HEX_AMP  |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_HEX_TAG  |
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES;

    return \json_encode($data, $escapeChars ? $options : 0);
}
