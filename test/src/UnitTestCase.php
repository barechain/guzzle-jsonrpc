<?php

declare(strict_types=1);

namespace Barechain\GuzzleHttp\JsonRpc\Test;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class UnitTestCase extends TestCase
{
    /**
     * Mock before event
     */
    protected function mockBeforeEvent(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Event\BeforeEvent');
    }

    /**
     * Mock complete event
     */
    protected function mockCompleteEvent(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Event\CompleteEvent');
    }

    /**
     * Mock error event
     */
    protected function mockErrorEvent(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Event\ErrorEvent');
    }

    /**
     * Mock http client
     */
    protected function mockHttpClient(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\ClientInterface');
    }

    /**
     * Mock http handler
     */
    protected function mockHttpHandler(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\HandlerStack');
    }

    /**
     * Mock message factory
     */
    protected function mockMessageFactory(): MockInterface
    {
        return Mockery::mock('Barechain\GuzzleHttp\JsonRpc\Message\MessageFactoryInterface');
    }

    /**
     * Mock promise
     */
    protected function mockPromise(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Promise\PromiseInterface');
    }

    /**
     * Mock request
     */
    protected function mockRequest(): Mockery\MockInterface
    {
        return Mockery::mock('Barechain\GuzzleHttp\JsonRpc\Message\RequestInterface');
    }

    /**
     * Mock response
     */
    protected function mockResponse(): MockInterface
    {
        return Mockery::mock('Barechain\GuzzleHttp\JsonRpc\Message\ResponseInterface');
    }

    /**
     * Mock stream
     */
    protected function mockStream(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Stream\StreamInterface');
    }

    /**
     * Mock transaction
     */
    protected function mockTransaction(): MockInterface
    {
        return Mockery::mock('GuzzleHttp\Adapter\TransactionInterface');
    }

    /**
     * Clean up mockery container
     */
    public function tearDown(): void
    {
        Mockery::close();
    }
}
