<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Middleware;

use Barechain\GuzzleHttp\JsonRpc\Exception\{ClientException, ServerException};
use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class RpcErrorMiddlewareTest extends UnitTestCase
{
    private mixed $request;
    private mixed $response;
    private RpcErrorMiddleware $middleware;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->request = $this->mockRequest();
        $this->response = $this->mockResponse();
        $this->middleware = new RpcErrorMiddleware();
    }

    /**
     * Test apply request
     */
    public function testApplyRequest(): void
    {
        $this->assertSame($this->request, $this->middleware->applyRequest($this->request, []));
    }

    /**
     * Test apply response throws ClientException
     */
    public function testApplyResponseThrowsClientException(): void
    {
        $this->expectException(ClientException::class);
        $this->response->shouldReceive('getRpcErrorCode')->times(2)->withNoArgs()->andReturn(-32600);
        $this->request->shouldReceive('getRequestTarget')->once()->withNoArgs()->andReturn('http://foo');
        $this->request->shouldReceive('getRpcMethod')->once()->withNoArgs()->andReturn('foo');
        $this->response->shouldReceive('getRpcErrorMessage')->once()->withNoArgs()->andReturn('bar');
        $this->response->shouldReceive('getStatusCode')->once()->withNoArgs()->andReturn(200);

        $this->middleware->applyResponse($this->request, $this->response, ['rpc_error' => true]);
    }

    /**
     * Test apply response throws ServerException
     */
    public function testApplyResponseThrowsServerException()
    {
        $this->expectException(ServerException::class);
        $this->response->shouldReceive('getRpcErrorCode')->times(2)->withNoArgs()->andReturn(-32000);
        $this->request->shouldReceive('getRequestTarget')->once()->withNoArgs()->andReturn('http://foo');
        $this->request->shouldReceive('getRpcMethod')->once()->withNoArgs()->andReturn('foo');
        $this->response->shouldReceive('getRpcErrorMessage')->once()->withNoArgs()->andReturn('bar');
        $this->response->shouldReceive('getStatusCode')->once()->withNoArgs()->andReturn(200);

        $this->middleware->applyResponse($this->request, $this->response, ['rpc_error' => true]);
    }

    /**
     * Test apply response no error
     */
    public function testApplyResponseNoError(): void
    {
        $this->response->shouldReceive('getRpcErrorCode')->once()->withNoArgs()->andReturn(null);

        $this->assertSame(
            $this->response,
            $this->middleware->applyResponse(
                $this->request,
                $this->response,
                [
                    'rpc_error' => true
                ]
            )
        );
    }

    /**
     * Test apply response no option
     */
    public function testApplyResponseNoOption(): void
    {
        $this->assertSame($this->response, $this->middleware->applyResponse($this->request, $this->response, []));
    }
}
