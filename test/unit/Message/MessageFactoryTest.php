<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Message;

use Barechain\GuzzleHttp\JsonRpc\ClientInterface;
use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class MessageFactoryTest extends UnitTestCase
{
    private MessageFactory $factory;

    /**
     * Setup
     */
    public function setup(): void
    {
        $this->factory = new MessageFactory();
    }

    /**
     * Test interface
     */
    public function testInterface(): void
    {
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\MessageFactoryInterface', $this->factory);
    }

    /**
     * Test create request
     */
    public function testCreateRequest(): void
    {
        $method = RequestInterface::REQUEST;
        $uri = 'http://bar';
        $options = [
            'method' => 'baz'
        ];

        $request = $this->factory->createRequest($method, $uri, [], $options);
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\RequestInterface', $request);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('http://bar', (string) $request->getUri());
        $this->assertEquals('baz', $request->getRpcMethod());
    }

    /**
     * Test create response
     */
    public function testCreateResponse(): void
    {
        $status = 200;
        $headers = ['Content-Type'=>'application/json'];

        $response = $this->factory->createResponse($status, $headers);
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface', $response);
        $this->assertEquals($status, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('[]', (string) $response->getBody());
        $this->assertEquals(null, $response->getRpcVersion());
        $this->assertEquals(null, $response->getRpcResult());
        $this->assertEquals(null, $response->getRpcId());
        $this->assertEquals(null, $response->getRpcErrorCode());
        $this->assertEquals(null, $response->getRpcErrorMessage());
    }

    /**
     * Test create response with options
     */
    public function testCreateResponseWithOptions(): void
    {
        $status = 200;
        $headers = ['Content-Type'=>'application/json'];
        $options = [
            'jsonrpc' => ClientInterface::SPEC,
            'result' => 'foo',
            'id' => 123
        ];

        $response = $this->factory->createResponse($status, $headers, $options);
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface', $response);
        $this->assertEquals($status, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals(json_encode($options), (string) $response->getBody());
        $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
        $this->assertEquals('foo', $response->getRpcResult());
        $this->assertEquals(123, $response->getRpcId());
        $this->assertEquals(null, $response->getRpcErrorCode());
        $this->assertEquals(null, $response->getRpcErrorMessage());
    }
}
