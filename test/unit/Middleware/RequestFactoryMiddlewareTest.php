<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class RequestFactoryMiddlewareTest extends UnitTestCase
{
    private mixed $request;
    private mixed $response;
    private mixed $factory;
    private RequestFactoryMiddleware $middleware;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->request = $this->mockRequest();
        $this->response = $this->mockResponse();
        $this->factory = $this->mockMessageFactory();

        $this->middleware = new RequestFactoryMiddleware($this->factory);
    }

    /**
     * Test apply request
     */
    public function testApplyRequest(): void
    {
        $newRequest = clone $this->request;
        $this->factory->shouldReceive('fromRequest')->once()->with($this->request)->andReturn($newRequest);

        $this->assertSame($newRequest, $this->middleware->applyRequest($this->request, []));
    }

    /**
     * Test apply response
     */
    public function testApplyResponse(): void
    {
        $this->assertSame($this->response, $this->middleware->applyResponse($this->request, $this->response, []));
    }
}
