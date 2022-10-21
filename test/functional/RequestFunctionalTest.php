<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Exception\ClientException;
use Barechain\GuzzleHttp\JsonRpc\Test\FunctionalTestCase;

class RequestFunctionalTest extends FunctionalTestCase
{
    private Client $client;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->client = $this->createClient();
    }

    /**
     * Test concat request
     */
    public function testConcatRequest(): void
    {
        $id = 123;
        $method = 'concat';
        $params = ['foo'=>'abc', 'bar'=>'def'];
        $request = $this->client->request($id, $method, $params);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals($params, $request->getRpcParams());

        $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
        $this->assertEquals(implode('', $params), $response->getRpcResult());
        $this->assertEquals($id, $response->getRpcId());
        $this->assertEquals(null, $response->getRpcErrorCode());
        $this->assertEquals(null, $response->getRpcErrorMessage());
        $this->assertEquals(null, $response->getRpcErrorData());
    }

    /**
     * Test concat async request
     */
    public function testConcatAsyncRequest(): void
    {
        $id = 123;
        $method = 'concat';
        $params = ['foo'=>'abc', 'bar'=>'def'];
        $request = $this->client->request($id, $method, $params);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $id, $method, $params) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals($id, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals($params, $request->getRpcParams());

            $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
            $this->assertEquals(implode('', $params), $response->getRpcResult());
            $this->assertEquals($id, $response->getRpcId());
            $this->assertEquals(null, $response->getRpcErrorCode());
            $this->assertEquals(null, $response->getRpcErrorMessage());
            $this->assertEquals(null, $response->getRpcErrorData());
        })->wait();
    }

    /**
     * Test sum request
     */
    public function testSumRequest(): void
    {
        $id = 'abc';
        $method = 'sum';
        $params = ['foo'=>123, 'bar'=>456];
        $request = $this->client->request($id, $method, $params);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals($params, $request->getRpcParams());

        $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
        $this->assertEquals(array_sum($params), $response->getRpcResult());
        $this->assertEquals($id, $response->getRpcId());
        $this->assertEquals(null, $response->getRpcErrorCode());
        $this->assertEquals(null, $response->getRpcErrorMessage());
        $this->assertEquals(null, $response->getRpcErrorData());
    }

    /**
     * Test sum async request
     */
    public function testSumAsyncRequest(): void
    {
        $id = 'abc';
        $method = 'sum';
        $params = ['foo'=>123, 'bar'=>456];
        $request = $this->client->request($id, $method, $params);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $id, $method, $params) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals($id, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals($params, $request->getRpcParams());

            $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
            $this->assertEquals(array_sum($params), $response->getRpcResult());
            $this->assertEquals($id, $response->getRpcId());
            $this->assertEquals(null, $response->getRpcErrorCode());
            $this->assertEquals(null, $response->getRpcErrorMessage());
            $this->assertEquals(null, $response->getRpcErrorData());
        })->wait();
    }

    /**
     * Test foo request
     */
    public function testFooRequest(): void
    {
        $id = 'abc';
        $method = 'foo';
        $request = $this->client->request($id, $method, []);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals(null, $request->getRpcParams());

        $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
        $this->assertEquals('foo', $response->getRpcResult());
        $this->assertEquals($id, $response->getRpcId());
        $this->assertEquals(null, $response->getRpcErrorCode());
        $this->assertEquals(null, $response->getRpcErrorMessage());
        $this->assertEquals(null, $response->getRpcErrorData());
    }

    /**
     * Test foo async request
     */
    public function testFooAsyncRequest(): void
    {
        $id = 'abc';
        $method = 'foo';
        $request = $this->client->request($id, $method, []);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $id, $method) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals($id, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals(null, $request->getRpcParams());

            $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
            $this->assertEquals('foo', $response->getRpcResult());
            $this->assertEquals($id, $response->getRpcId());
            $this->assertEquals(null, $response->getRpcErrorCode());
            $this->assertEquals(null, $response->getRpcErrorMessage());
            $this->assertEquals(null, $response->getRpcErrorData());
        })->wait();
    }

    /**
     * Test bar request
     */
    public function testBarRequest(): void
    {
        $id = 'abc';
        $method = 'bar';
        $request = $this->client->request($id, $method, []);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals(null, $request->getRpcParams());

        $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
        $this->assertEquals(null, $response->getRpcResult());
        $this->assertEquals($id, $response->getRpcId());
        $this->assertTrue(is_int($response->getRpcErrorCode()));
        $this->assertTrue(is_string($response->getRpcErrorMessage()));
        $this->assertEquals(null, $response->getRpcErrorData());
    }

    /**
     * Test bar async request
     */
    public function testBarAsyncRequest(): void
    {
        $id = 'abc';
        $method = 'bar';
        $request = $this->client->request($id, $method, []);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $id, $method) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals($id, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals(null, $request->getRpcParams());

            $this->assertEquals(ClientInterface::SPEC, $response->getRpcVersion());
            $this->assertEquals(null, $response->getRpcResult());
            $this->assertEquals($id, $response->getRpcId());
            $this->assertTrue(is_int($response->getRpcErrorCode()));
            $this->assertTrue(is_string($response->getRpcErrorMessage()));
            $this->assertEquals(null, $response->getRpcErrorData());
        })->wait();
    }

    /**
     * Test bar request throws
     *
     */
    public function testBarRequestThrows(): void
    {
        $this->expectException(ClientException::class);

        $id = 'abc';
        $method = 'bar';
        $client = $this->createClient(null, ['rpc_error' => true]);
        $request = $client->request($id, $method, []);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals(null, $request->getRpcParams());

        $client->send($request);
    }

    /**
     * Test bar async request is rejected
     */
    public function testBarAsyncRequestIsRejected(): void
    {
        $id = 'abc';
        $method = 'bar';
        $client = $this->createClient(null, ['rpc_error' => true]);
        $request = $client->request($id, $method, []);
        $promise = $client->sendAsync($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals(null, $request->getRpcParams());

        $promise->then(function () use ($request, $id, $method) {
            $this->fail('This promise should not be fulfilled');
        }, function ($reason) {
            $this->assertInstanceOf(ClientException::class, $reason);
        })->wait();
    }
}
