<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Exception;

use Barechain\GuzzleHttp\JsonRpc\Test\UnitTestCase;

class RequestExceptionTest extends UnitTestCase
{
    private mixed $request;
    private mixed $response;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->request = $this->mockRequest();
        $this->response = $this->mockResponse();
    }

    /**
     * Data create ClientException
     *
     * @return array
     */
    public function dataCreateClientException(): array
    {
        return [[-32600], [-32601], [-32602], [-32700]];
    }

    /**
     * Data create ServerException
     *
     * @return array
     */
    public function dataCreateServerException(): array
    {
        return [[-32603], [-32000], [-32099], [-10000]];
    }

    /**
     * Test create ClientException
     *
     * @dataProvider dataCreateClientException
     *
     * @param int $code
     */
    public function testCreateClientException(int $code): void
    {
        $this->request->shouldReceive('getRequestTarget')->once()->withNoArgs()->andReturn('http://foo');
        $this->request->shouldReceive('getRpcMethod')->once()->withNoArgs()->andReturn('foo');
        $this->response->shouldReceive('getRpcErrorCode')->once()->withNoArgs()->andReturn($code);
        $this->response->shouldReceive('getRpcErrorMessage')->once()->withNoArgs()->andReturn('bar');
        $this->response->shouldReceive('getStatusCode')->once()->withNoArgs()->andReturn(200);

        $exception = RequestException::create($this->request, $this->response);
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Exception\ClientException', $exception);
    }

    /**
     * Test create ServerException
     *
     * @dataProvider dataCreateServerException
     *
     * @param int $code
     */
    public function testCreateServerException(int $code): void
    {
        $this->request->shouldReceive('getRequestTarget')->once()->withNoArgs()->andReturn('http://foo');
        $this->request->shouldReceive('getRpcMethod')->once()->withNoArgs()->andReturn('foo');
        $this->response->shouldReceive('getRpcErrorCode')->once()->withNoArgs()->andReturn($code);
        $this->response->shouldReceive('getRpcErrorMessage')->once()->withNoArgs()->andReturn('bar');
        $this->response->shouldReceive('getStatusCode')->once()->withNoArgs()->andReturn(200);

        $exception = RequestException::create($this->request, $this->response);
        $this->assertInstanceOf('Barechain\GuzzleHttp\JsonRpc\Exception\ServerException', $exception);
    }
}
