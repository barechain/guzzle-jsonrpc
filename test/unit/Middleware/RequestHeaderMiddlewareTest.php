<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class RequestHeaderMiddlewareTest extends UnitTestCase
{
    private mixed $request;
    private mixed $response;
    private RequestHeaderMiddleware $middleware;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->request = $this->mockRequest();
        $this->response = $this->mockResponse();

        $this->middleware = new RequestHeaderMiddleware();
    }

    /**
     * Test apply request
     */
    public function testApplyRequest(): void
    {
        $requestA = clone $this->request;
        $requestB = clone $requestA;

        $this->request->shouldReceive('withHeader')
            ->once()
            ->with('Accept-Encoding', 'gzip;q=1.0,deflate;q=0.6,identity;q=0.3')
            ->andReturn($requestA);

        $requestA->shouldReceive('withHeader')->once()->with('Content-Type', 'application/json')->andReturn($requestB);

        $this->assertSame($requestB, $this->middleware->applyRequest($this->request, []));
    }

    /**
     * Test apply response
     */
    public function testApplyResponse(): void
    {
        $this->assertSame($this->response, $this->middleware->applyResponse($this->request, $this->response, []));
    }
}
