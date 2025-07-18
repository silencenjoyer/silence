<?php

declare(strict_types=1);

namespace Silence\Http\Tests\Handlers;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Silence\Http\Handlers\MiddlewareRunner;

class MiddlewareRunnerTest extends TestCase
{
    private ServerRequestInterface $request;
    private ResponseInterface $response;
    private RequestHandlerInterface $handler;

    /**
     * {@inheritDoc}
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
    }

    protected function createRunner(array $middlewares, RequestHandlerInterface $finalHandler): MiddlewareRunner
    {
        return new MiddlewareRunner($middlewares, $finalHandler);
    }

    public function testCallsFinalHandlerIfNoMiddleware(): void
    {
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($this->response)
        ;

        $runner = $this->createRunner([], $this->handler);

        $result = $runner->handle($this->request);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws Exception
     */
    public function testCallsFirstMiddleware(): void
    {
        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->expects($this->once())
            ->method('process')
            ->with($this->request)
            ->willReturn($this->response)
        ;

        $runner = $this->createRunner([$middleware], $this->handler);
        $result = $runner->handle($this->request);

        $this->assertSame($this->response, $result);
    }

    /**
     * @throws Exception
     */
    public function testMiddlewareChainIsExecutedInOrder(): void
    {
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($this->response)
        ;

        $sequence = 0;

        $middlewares = [];
        for ($i = 0; $i < 10; $i++) {
            $middleware = $this->createMock(MiddlewareInterface::class);
            $middleware->expects($this->once())
                ->method('process')
                ->with($this->request)
                ->willReturnCallback(
                    function (ServerRequestInterface $request, RequestHandlerInterface $handler) use (&$sequence, $i) {
                        $sequence++;
                        $this->assertSame($i + 1, $sequence);
                        return $handler->handle($request);
                    }
                );

            $middlewares[] = $middleware;
        }

        $runner = $this->createRunner($middlewares, $this->handler);
        $result = $runner->handle($this->request);

        $this->assertSame($this->response, $result);
        $this->assertSame(count($middlewares), $sequence);
    }
}
