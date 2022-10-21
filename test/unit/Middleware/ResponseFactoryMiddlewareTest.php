<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class ResponseFactoryMiddlewareTest extends UnitTestCase
{
    private mixed $request;
    private mixed $response;
    private mixed $factory;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->request = $this->mockRequest();
        $this->response = $this->mockResponse();
        $this->factory = $this->mockMessageFactory();

        $this->middleware = new ResponseFactoryMiddleware($this->factory);
    }

    /**
     * Test apply request
     */
    public function testApplyRequest(): void
    {
        $this->assertSame($this->request, $this->middleware->applyRequest($this->request, []));
    }

    /**
     * Test apply response
     */
    public function testApplyResponse(): void
    {
        $newResponse = clone $this->response;
        $this->factory->shouldReceive('fromResponse')->once()->with($this->response)->andReturn($newResponse);

        $this->assertSame($newResponse, $this->middleware->applyResponse($this->request, $this->response, []));
    }
}
