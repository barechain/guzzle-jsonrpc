<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc;

use Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface;
use Barechain\GuzzleHttp\JsonRpc\Test\FunctionalTestCase;

class BatchFunctionalTest extends FunctionalTestCase
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
     * Test batch request with one child
     */
    public function testBatchRequestWithOneChild(): void
    {
        $id = 'abc';
        $method = 'sum';
        $params = ['foo'=>123, 'bar'=>456];
        $request = $this->client->request($id, $method, $params);
        $responses = $this->client->sendAll([$request]);

        $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
        $this->assertEquals($id, $request->getRpcId());
        $this->assertEquals($method, $request->getRpcMethod());
        $this->assertEquals($params, $request->getRpcParams());

        $this->assertTrue(is_array($responses));
        $this->assertEquals(ClientInterface::SPEC, $responses[0]->getRpcVersion());
        $this->assertEquals(array_sum($params), $responses[0]->getRpcResult());
        $this->assertEquals($id, $responses[0]->getRpcId());
        $this->assertEquals(null, $responses[0]->getRpcErrorCode());
        $this->assertEquals(null, $responses[0]->getRpcErrorMessage());
    }

    /**
     * Test async batch request with one child
     */
    public function testAsyncBatchRequestWithOneChild(): void
    {
        $id = 'abc';
        $method = 'sum';
        $params = ['foo' => 123, 'bar' => 456];
        $request = $this->client->request($id, $method, $params);
        $promise = $this->client->sendAllAsync([$request]);

        $promise->then(function ($responses) use ($request, $id, $method, $params) {
            $this->assertEquals(ClientInterface::SPEC, $request->getRpcVersion());
            $this->assertEquals($id, $request->getRpcId());
            $this->assertEquals($method, $request->getRpcMethod());
            $this->assertEquals($params, $request->getRpcParams());

            $this->assertTrue(is_array($responses));
            $this->assertEquals(ClientInterface::SPEC, $responses[0]->getRpcVersion());
            $this->assertEquals(array_sum($params), $responses[0]->getRpcResult());
            $this->assertEquals($id, $responses[0]->getRpcId());
            $this->assertEquals(null, $responses[0]->getRpcErrorCode());
            $this->assertEquals(null, $responses[0]->getRpcErrorMessage());
        })->wait();
    }

    /**
     * Get response from array
     */
    private function getResponseFromArray(array $responses, string|int $id): ResponseInterface
    {
        $filtered = array_values(array_filter($responses, function (ResponseInterface $response) use ($id) {
            return $response->getRpcId() == $id;
        }));

        if (count($filtered) === 0) {
            $this->fail('Unable to find response with id:' . $id);
        }
        return reset($filtered);
    }

    /**
     * Test batch request with multiple children
     */
    public function testBatchRequestWithMultipleChildren(): void
    {
        $idA = 123;
        $idC = 'abc';
        $idD = 'def';
        $methodA = 'concat';
        $methodB = 'nofify';
        $methodC = 'sum';
        $methodD = 'bar';
        $paramsA = ['foo'=>'abc', 'bar'=>'def'];
        $paramsB = ['foo'=>false];
        $paramsC = ['foo'=>123, 'bar'=>456];
        $paramsD = ['foo'=>123, 'bar'=>456];
        $requestA = $this->client->request($idA, $methodA, $paramsA);
        $requestB = $this->client->notification($methodB, $paramsB);
        $requestC = $this->client->request($idC, $methodC, $paramsC);
        $requestD = $this->client->request($idD, $methodD, $paramsD);
        $responses = $this->client->sendAll([$requestA, $requestB, $requestC, $requestD]);

        $this->assertEquals(ClientInterface::SPEC, $requestA->getRpcVersion());
        $this->assertEquals($idA, $requestA->getRpcId());
        $this->assertEquals($methodA, $requestA->getRpcMethod());
        $this->assertEquals($paramsA, $requestA->getRpcParams());
        $this->assertEquals(ClientInterface::SPEC, $requestB->getRpcVersion());
        $this->assertEquals(null, $requestB->getRpcId());
        $this->assertEquals($methodB, $requestB->getRpcMethod());
        $this->assertEquals($paramsB, $requestB->getRpcParams());
        $this->assertEquals(ClientInterface::SPEC, $requestC->getRpcVersion());
        $this->assertEquals($idC, $requestC->getRpcId());
        $this->assertEquals($methodC, $requestC->getRpcMethod());
        $this->assertEquals($paramsC, $requestC->getRpcParams());
        $this->assertEquals(ClientInterface::SPEC, $requestD->getRpcVersion());
        $this->assertEquals($idD, $requestD->getRpcId());
        $this->assertEquals($methodD, $requestD->getRpcMethod());
        $this->assertEquals($paramsD, $requestD->getRpcParams());

        $this->assertTrue(is_array($responses));
        $this->assertEquals(3, count($responses));

        $responseA = $this->getResponseFromArray($responses, $idA);
        $responseC = $this->getResponseFromArray($responses, $idC);
        $responseD = $this->getResponseFromArray($responses, $idD);

        $this->assertEquals(ClientInterface::SPEC, $responseA->getRpcVersion());
        $this->assertEquals(implode('', $paramsA), $responseA->getRpcResult());
        $this->assertEquals($idA, $responseA->getRpcId());
        $this->assertEquals(null, $responseA->getRpcErrorCode());
        $this->assertEquals(null, $responseA->getRpcErrorMessage());
        $this->assertEquals(ClientInterface::SPEC, $responseC->getRpcVersion());
        $this->assertEquals(array_sum($paramsC), $responseC->getRpcResult());
        $this->assertEquals($idC, $responseC->getRpcId());
        $this->assertEquals(null, $responseC->getRpcErrorCode());
        $this->assertEquals(null, $responseC->getRpcErrorMessage());
        $this->assertEquals(ClientInterface::SPEC, $responseD->getRpcVersion());
        $this->assertEquals(null, $responseD->getRpcResult());
        $this->assertEquals($idD, $responseD->getRpcId());
        $this->assertTrue(is_int($responseD->getRpcErrorCode()));
        $this->assertTrue(is_string($responseD->getRpcErrorMessage()));
    }

    /**
     * Test async batch request with multiple children
     */
    public function testAsyncBatchRequestWithMultipleChildren(): void
    {
        $idA = 123;
        $idC = 'abc';
        $idD = 'def';
        $methodA = 'concat';
        $methodB = 'nofify';
        $methodC = 'sum';
        $methodD = 'bar';
        $paramsA = ['foo'=>'abc', 'bar'=>'def'];
        $paramsB = ['foo'=>false];
        $paramsC = ['foo'=>123, 'bar'=>456];
        $paramsD = ['foo'=>123, 'bar'=>456];
        $requestA = $this->client->request($idA, $methodA, $paramsA);
        $requestB = $this->client->notification($methodB, $paramsB);
        $requestC = $this->client->request($idC, $methodC, $paramsC);
        $requestD = $this->client->request($idD, $methodD, $paramsD);
        $promise = $this->client->sendAllAsync([$requestA, $requestB, $requestC, $requestD]);

        $promise->then(function ($responses) use ($requestA, $requestB, $requestC, $requestD, $idA, $idC, $idD, $methodA, $methodB, $methodC, $methodD, $paramsA, $paramsB, $paramsC, $paramsD) {
            $this->assertEquals(ClientInterface::SPEC, $requestA->getRpcVersion());
            $this->assertEquals($idA, $requestA->getRpcId());
            $this->assertEquals($methodA, $requestA->getRpcMethod());
            $this->assertEquals($paramsA, $requestA->getRpcParams());
            $this->assertEquals(ClientInterface::SPEC, $requestB->getRpcVersion());
            $this->assertEquals(null, $requestB->getRpcId());
            $this->assertEquals($methodB, $requestB->getRpcMethod());
            $this->assertEquals($paramsB, $requestB->getRpcParams());
            $this->assertEquals(ClientInterface::SPEC, $requestC->getRpcVersion());
            $this->assertEquals($idC, $requestC->getRpcId());
            $this->assertEquals($methodC, $requestC->getRpcMethod());
            $this->assertEquals($paramsC, $requestC->getRpcParams());
            $this->assertEquals(ClientInterface::SPEC, $requestD->getRpcVersion());
            $this->assertEquals($idD, $requestD->getRpcId());
            $this->assertEquals($methodD, $requestD->getRpcMethod());
            $this->assertEquals($paramsD, $requestD->getRpcParams());

            $this->assertTrue(is_array($responses));
            $this->assertEquals(3, count($responses));

            $responseA = $this->getResponseFromArray($responses, $idA);
            $responseC = $this->getResponseFromArray($responses, $idC);
            $responseD = $this->getResponseFromArray($responses, $idD);

            $this->assertEquals(ClientInterface::SPEC, $responseA->getRpcVersion());
            $this->assertEquals(implode('', $paramsA), $responseA->getRpcResult());
            $this->assertEquals($idA, $responseA->getRpcId());
            $this->assertEquals(null, $responseA->getRpcErrorCode());
            $this->assertEquals(null, $responseA->getRpcErrorMessage());
            $this->assertEquals(ClientInterface::SPEC, $responseC->getRpcVersion());
            $this->assertEquals(array_sum($paramsC), $responseC->getRpcResult());
            $this->assertEquals($idC, $responseC->getRpcId());
            $this->assertEquals(null, $responseC->getRpcErrorCode());
            $this->assertEquals(null, $responseC->getRpcErrorMessage());
            $this->assertEquals(ClientInterface::SPEC, $responseD->getRpcVersion());
            $this->assertEquals(null, $responseD->getRpcResult());
            $this->assertEquals($idD, $responseD->getRpcId());
            $this->assertTrue(is_int($responseD->getRpcErrorCode()));
            $this->assertTrue(is_string($responseD->getRpcErrorMessage()));
        })->wait();
    }
}
