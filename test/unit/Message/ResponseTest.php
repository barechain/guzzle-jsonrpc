<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class ResponseTest extends UnitTestCase
{
    /**
     * Test interface
     */
    public function testInterface(): void
    {
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface', new Response(200));
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', new Response(200));
    }

    /**
     * Test get rpc id
     */
    public function testGetRpcId(): void
    {
        $response = new Response(200, [], json_encode([
            'id' => 123
        ]));

        $this->assertEquals(123, $response->getRpcId());
    }

    /**
     * Test get rpc id is null
     */
    public function testGetRpcIdIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcId());
    }

    /**
     * Test get rpc error code
     */
    public function testGetRpcErrorCode(): void
    {
        $response = new Response(200, [], json_encode([
            'error' => [
                'code' => 123
            ]
        ]));

        $this->assertEquals(123, $response->getRpcErrorCode());
    }

    /**
     * Test get rpc error code is null
     */
    public function testGetRpcErrorCodeIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcErrorCode());
    }

    /**
     * Test get rpc error message
     */
    public function testGetRpcErrorMessage(): void
    {
        $response = new Response(200, [], json_encode([
            'error' => [
                'message' => 'foo'
            ]
        ]));

        $this->assertEquals('foo', $response->getRpcErrorMessage());
    }

    /**
     * Test get rpc error message is null
     */
    public function testGetRpcErrorMessageIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcErrorMessage());
    }

    /**
     * Test get rpc error data
     */
    public function testGetRpcErrorData(): void
    {
        $response = new Response(200, [], json_encode([
            'error' => [
                'data' => []
            ]
        ]));

        $this->assertEquals([], $response->getRpcErrorData());
    }

    /**
     * Test get rpc error data is null
     */
    public function testGetRpcErrorDataIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcErrorData());
    }

    /**
     * Test get rpc result
     */
    public function testGetRpcResult(): void
    {
        $response = new Response(200, [], json_encode([
            'result' => 'foo'
        ]));

        $this->assertEquals('foo', $response->getRpcResult());
    }

    /**
     * Test get rpc result is null
     */
    public function testGetRpcResultIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcResult());
    }

    /**
     * Test get rpc version
     */
    public function testGetRpcVersion(): void
    {
        $response = new Response(200, [], json_encode([
            'jsonrpc' => 'foo'
        ]));

        $this->assertEquals('foo', $response->getRpcVersion());
    }

    /**
     * Test get rpc version is null
     */
    public function testGetRpcVersionIsNull(): void
    {
        $response = new Response(200);

        $this->assertNull($response->getRpcVersion());
    }
}
