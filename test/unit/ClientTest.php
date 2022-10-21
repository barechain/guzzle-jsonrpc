<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Message\RequestInterface;
use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;
use Mockery;

class ClientTest extends UnitTestCase
{
    private mixed $httpClient;
    private mixed $messageFactory;
    private Client $client;

    /**
     * Setup
     */
    public function setup(): void
    {
        $this->httpClient = $this->mockHttpClient();
        $httpHandler = $this->mockHttpHandler();
        $this->messageFactory = $this->mockMessageFactory();

        $this->httpClient->shouldReceive('getConfig')->once()->with('handler')->andReturn($httpHandler);
        $httpHandler->shouldReceive('push')->times(4);

        $this->client = new Client($this->httpClient, $this->messageFactory);
    }

    /**
     * Test interface
     */
    public function testInterface(): void
    {
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\ClientInterface', $this->client);
    }

    /**
     * Test static factory
     */
    public function testStaticFactory(): void
    {
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\ClientInterface', Client::factory('http://foo'));
    }

    /**
     * Test notification
     */
    public function testNotification(): void
    {
        $request = $this->mockRequest();
        $jsonrpc = ['jsonrpc' => ClientInterface::SPEC, 'method' => 'foo'];
        $type = RequestInterface::NOTIFICATION;
        $uri = 'http://foo';

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);

        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], $jsonrpc)
            ->andReturn($request);

        $this->assertSame($request, $this->client->notification('foo'));
    }

    /**
     * Test notification with params
     */
    public function testNotificationWithParams(): void
    {
        $request = $this->mockRequest();
        $jsonrpc = ['jsonrpc' => ClientInterface::SPEC, 'method' => 'foo', 'params' => ['bar' => true]];
        $type = RequestInterface::NOTIFICATION;
        $uri = 'http://foo';

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);
        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], $jsonrpc)
            ->andReturn($request);

        $this->assertSame($request, $this->client->notification('foo', ['bar' => true]));
    }

    /**
     * Test request
     */
    public function testRequest(): void
    {
        $request = $this->mockRequest();
        $jsonrpc = ['jsonrpc' => ClientInterface::SPEC, 'method' => 'foo', 'id' => 123];
        $type = RequestInterface::REQUEST;
        $uri = 'http://foo';

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);
        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], $jsonrpc)
            ->andReturn($request);

        $this->assertSame($request, $this->client->request(123, 'foo'));
    }

    /**
     * Test request with params
     */
    public function testRequestWithParams(): void
    {
        $request = $this->mockRequest();
        $jsonrpc = ['jsonrpc' => ClientInterface::SPEC, 'method' => 'foo', 'params' => ['bar' => true], 'id' => 123];
        $type = RequestInterface::REQUEST;
        $uri = 'http://foo';

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);
        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], $jsonrpc)
            ->andReturn($request);

        $this->assertSame($request, $this->client->request(123, 'foo', ['bar'=>true]));
    }

    /**
     * Test request with empty params
     */
    public function testRequestWithEmptyParams(): void
    {
        $request = $this->mockRequest();
        $jsonrpc = ['jsonrpc'=>ClientInterface::SPEC, 'method'=>'foo', 'id'=>123];
        $type = RequestInterface::REQUEST;
        $uri = 'http://foo';

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);

        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], $jsonrpc)
            ->andReturn($request);

        $this->assertSame($request, $this->client->request(123, 'foo', []));
    }

    /**
     * Test send notification
     */
    public function testSendNotification(): void
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $promise = $this->mockPromise();

        $request->shouldReceive('getRpcId')->once()->withNoArgs()->andReturn(null);
        $this->httpClient->shouldReceive('sendAsync')->once()->with($request)->andReturn($promise);
        $promise->shouldReceive('then')->once()->with(Mockery::on(function ($args) use ($response) {
            return null === $args($response);
        }))->andReturn($promise);
        $promise->shouldReceive('wait')->once()->withNoArgs()->andReturn(null);

        $this->assertNull($this->client->send($request));
    }

    /**
     * Test send notification async
     */
    public function testSendNotificationAsync(): void
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $promise = $this->mockPromise();

        $request->shouldReceive('getRpcId')->once()->withNoArgs()->andReturn(null);
        $this->httpClient->shouldReceive('sendAsync')->once()->with($request)->andReturn($promise);
        $promise->shouldReceive('then')->once()->with(Mockery::on(function ($args) use ($response) {
            return null === $args($response);
        }))->andReturn($promise);

        $this->assertSame($promise, $this->client->sendAsync($request));
    }

    /**
     * Test send request
     */
    public function testSendRequest(): void
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $promise = $this->mockPromise();

        $request->shouldReceive('getRpcId')->once()->withNoArgs()->andReturn('foo');
        $this->httpClient->shouldReceive('sendAsync')->once()->with($request)->andReturn($promise);
        $promise->shouldReceive('then')->once()->with(Mockery::on(function ($args) use ($response) {
            return $response === $args($response);
        }))->andReturn($promise);
        $promise->shouldReceive('wait')->once()->withNoArgs()->andReturn($response);

        $this->assertSame($response, $this->client->send($request));
    }

    /**
     * Test send request async
     */
    public function testSendRequestAsync(): void
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $promise = $this->mockPromise();

        $request->shouldReceive('getRpcId')->once()->withNoArgs()->andReturn('foo');
        $this->httpClient->shouldReceive('sendAsync')->once()->with($request)->andReturn($promise);
        $promise->shouldReceive('then')->once()->with(Mockery::on(function ($args) use ($response) {
            return $response === $args($response);
        }))->andReturn($promise);

        $this->assertSame($promise, $this->client->sendAsync($request));
    }

    /**
     * Test send all
     */
    public function testSendAll(): void
    {
        $promise = $this->mockPromise();
        $batchRequest = $this->mockRequest();
        $requestA = $this->mockRequest();
        $requestB = $this->mockRequest();
        $batchResponse = $this->mockResponse();
        $responseA = $this->mockResponse();
        $responseB = $this->mockResponse();

        $factory = $this->mockMessageFactory();
        $this->httpClient->messageFactory = $factory;

        $requestA->shouldReceive('getBody')->once()->withNoArgs()->andReturn('["foo"]');
        $requestB->shouldReceive('getBody')->once()->withNoArgs()->andReturn('["bar"]');

        $type = RequestInterface::BATCH;
        $uri = 'http://foo';

        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], [['foo'], ['bar']])
            ->andReturn($batchRequest);

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);
        $this->httpClient->shouldReceive('sendAsync')->once()->with($batchRequest)->andReturn($promise);

        $promise->shouldReceive('then')
            ->once()
            ->with(Mockery::on(function ($args) use ($batchResponse, $responseA, $responseB) {
                return [$responseA, $responseB] === $args($batchResponse);
            }))->andReturn($promise);

        $promise->shouldReceive('wait')->once()->withNoArgs()->andReturn([$responseA, $responseB]);

        $batchResponse->shouldReceive('getBody')->once()->withNoArgs()->andReturn('[["foo"], ["bar"]]');
        $batchResponse->shouldReceive('getStatusCode')->times(2)->withNoArgs()->andReturn(200);
        $batchResponse->shouldReceive('getHeaders')->times(2)->withNoArgs()->andReturn(['headers']);

        $this->messageFactory->shouldReceive('createResponse')
            ->once()
            ->with(200, ['headers'], ['foo'])
            ->andReturn($responseA);

        $this->messageFactory->shouldReceive('createResponse')
            ->once()
            ->with(200, ['headers'], ['bar'])
            ->andReturn($responseB);

        $this->assertSame([$responseA, $responseB], $this->client->sendAll([$requestA, $requestB]));
    }

    /**
     * Test send all async
     */
    public function testSendAllAsync(): void
    {
        $promise = $this->mockPromise();
        $batchRequest = $this->mockRequest();
        $requestA = $this->mockRequest();
        $requestB = $this->mockRequest();
        $batchResponse = $this->mockResponse();
        $responseA = $this->mockResponse();
        $responseB = $this->mockResponse();

        $factory = $this->mockMessageFactory();
        $this->httpClient->messageFactory = $factory;

        $requestA->shouldReceive('getBody')->once()->withNoArgs()->andReturn('["foo"]');
        $requestB->shouldReceive('getBody')->once()->withNoArgs()->andReturn('["bar"]');

        $type = RequestInterface::BATCH;
        $uri = 'http://foo';
        $this->messageFactory->shouldReceive('createRequest')
            ->once()
            ->with($type, $uri, [], [['foo'], ['bar']])
            ->andReturn($batchRequest);

        $this->httpClient->shouldReceive('getConfig')->once()->with('base_uri')->andReturn($uri);
        $this->httpClient->shouldReceive('getConfig')->once()->with('defaults')->andReturn([]);
        $this->httpClient->shouldReceive('sendAsync')->once()->with($batchRequest)->andReturn($promise);

        $promise->shouldReceive('then')
            ->once()
            ->with(Mockery::on(function ($args) use ($batchResponse, $responseA, $responseB) {
                return [$responseA, $responseB] === $args($batchResponse);
            }))->andReturn($promise);

        $batchResponse->shouldReceive('getBody')->once()->withNoArgs()->andReturn('[["foo"], ["bar"]]');
        $batchResponse->shouldReceive('getStatusCode')->times(2)->withNoArgs()->andReturn(200);
        $batchResponse->shouldReceive('getHeaders')->times(2)->withNoArgs()->andReturn(['headers']);

        $this->messageFactory->shouldReceive('createResponse')
            ->once()
            ->with(200, ['headers'], ['foo'])
            ->andReturn($responseA);

        $this->messageFactory->shouldReceive('createResponse')
            ->once()
            ->with(200, ['headers'], ['bar'])
            ->andReturn($responseB);

        $this->assertSame($promise, $this->client->sendAllAsync([$requestA, $requestB]));
    }
}
