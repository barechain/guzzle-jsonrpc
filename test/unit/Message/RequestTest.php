<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class RequestTest extends UnitTestCase
{
    /**
     * Test interface
     */
    public function testInterface(): void
    {
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\RequestInterface', new Request('foo', 'bar'));
        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', new Request('foo', 'bar'));
    }

    /**
     * Test get rpc id
     */
    public function testGetRpcId(): void
    {
        $request = new Request('foo', 'bar', [], json_encode([
            'id' => 123
        ]));

        $this->assertEquals(123, $request->getRpcId());
    }

    /**
     * Test get rpc is null
     */
    public function testGetRpcIdIsNull(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertNull($request->getRpcId());
    }

    /**
     * Test get rpc method
     */
    public function testGetRpcMethod(): void
    {
        $request = new Request('foo', 'bar', [], json_encode([
            'method' => 'foo'
        ]));

        $this->assertEquals('foo', $request->getRpcMethod());
    }

    /**
     * Test get rpc method is null
     */
    public function testGetRpcMethodIsNull(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertNull($request->getRpcMethod());
    }

    /**
     * Test get rpc params
     */
    public function testGetRpcParams(): void
    {
        $request = new Request('foo', 'bar', [], json_encode([
            'params' => [
                'foo' => 'bar'
            ]
        ]));

        $this->assertEquals(
            [
                'foo' => 'bar'
            ],
            $request->getRpcParams());
    }

    /**
     * Test get rpc params is null
     */
    public function testGetRpcParamsIsNull(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertNull($request->getRpcParams());
    }

    /**
     * Test get rpc version
     */
    public function testGetRpcVersion(): void
    {
        $request = new Request('foo', 'bar', [], json_encode([
            'jsonrpc' => 'foo'
        ]));

        $this->assertEquals('foo', $request->getRpcVersion());
    }

    /**
     * Test get rpc version is null
     */
    public function testGetRpcVersionIsNull(): void
    {
        $request = new Request('foo', 'bar');

        $this->assertNull($request->getRpcVersion());
    }
}
