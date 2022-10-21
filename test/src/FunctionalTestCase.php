<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Test;

use Barechain\GuzzleHttp\JsonRpc\Client;
use PHPUnit\Framework\TestCase;

class FunctionalTestCase extends TestCase
{
    protected string $defaultUrl = 'http://node:80';

    /**
     * Create client
     */
    public function createClient(?string $url = null, array $config = []): Client
    {
        return Client::factory($url ?: $this->defaultUrl, $config);
    }
}
