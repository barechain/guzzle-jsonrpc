<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Test\FunctionalTestCase;

class NotificationFunctionalTest extends FunctionalTestCase
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
     * Test notify request
     */
    public function testNotifyRequest(): void
    {
        $method = 'notify';
        $params = ['foo'=>true];
        $request = $this->client->notification($method, $params);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals(null, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals($params, $request->getRpcParams());

        $this->assertNull($response);
    }

    /**
     * Test async notify request
     */
    public function testAsyncNotifyRequest(): void
    {
        $method = 'notify';
        $params = ['foo'=>true];
        $request = $this->client->notification($method, $params);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $method, $params) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals(null, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals($params, $request->getRpcParams());

            $this->assertNull($response);
        })->wait();
    }

    /**
     * Test notify request with invalid params
     */
    public function testNotifyRequestWithInvalidParams(): void
    {
        $method = 'notify';
        $params = ['foo'=>'bar'];
        $request = $this->client->notification($method, $params);
        $response = $this->client->send($request);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals(null, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals($params, $request->getRpcParams());

        $this->assertNull($response);
    }

    /**
     * Test async notify request with invalid params
     */
    public function testAsyncNotifyRequestWithInvalidParams(): void
    {
        $method = 'notify';
        $params = ['foo'=>'bar'];
        $request = $this->client->notification($method, $params);
        $promise = $this->client->sendAsync($request);

        $promise->then(function ($response) use ($request, $method, $params) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals(null, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals($params, $request->getRpcParams());

            $this->assertNull($response);
        })->wait();
    }
}
