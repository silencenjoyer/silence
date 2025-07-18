<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare(strict_types=1);

namespace Silence\ErrorHandler\Tests\Middlewares;

use ErrorException;
use Error;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Silence\ErrorHandler\Middlewares\ThrowableMiddleware;
use Silence\ErrorHandler\Response\ThrowableResponseFactoryInterface;
use Throwable;

class ThrowableMiddlewareTest extends TestCase
{
    private ThrowableMiddleware $throwableMiddleware;
    private ThrowableResponseFactoryInterface $throwableResponseFactory;
    private ServerRequestInterface $request;
    private RequestHandlerInterface $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->throwableResponseFactory = $this->createMock(ThrowableResponseFactoryInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);

        $this->throwableMiddleware = new ThrowableMiddleware($this->throwableResponseFactory);
    }

    public static function exceptionProvider(): array
    {
        return [
            [new ErrorException()],
            [new \Exception()],
            [new Error()],
        ];
    }

    /**
     * @param Throwable $throwable
     * @return void
     */
    #[DataProvider('exceptionProvider')]
    public function testThrowableFactoryUsed(Throwable $throwable): void
    {
        $this->handler->method('handle')->willThrowException($throwable);

        $this->throwableResponseFactory->expects($this->once())->method('create');

        $this->throwableMiddleware->process($this->request, $this->handler);
    }

    /**
     * @throws Exception
     */
    public function testReturnsOnSuccess(): void
    {
        $expectedResponse = $this->createMock(ResponseInterface::class);
        $this->handler->method('handle')->willReturn($expectedResponse);

        $this->throwableResponseFactory->expects($this->never())->method('create');

        $actualResponse = $this->throwableMiddleware->process($this->request, $this->handler);

        $this->assertSame($expectedResponse, $actualResponse);
    }
}
