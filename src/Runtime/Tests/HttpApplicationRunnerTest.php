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

namespace Silence\Runtime\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\Middlewares\ExceptionHandlerMiddlewareInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Event\Types\OnResponseInterface;
use Silence\Event\Types\RequestCreatedInterface;
use Silence\Http\Emitters\EmitterInterface;
use Silence\Http\Handlers\MiddlewareRunnerFactoryInterface;
use Silence\Http\Handlers\MiddlewareRunnerInterface;
use Silence\Http\Handlers\RouteHandlerInterface;
use Silence\Http\Request\RequestFactoryInterface;
use Silence\Runtime\HttpApplicationRunner;

class HttpApplicationRunnerTest extends TestCase
{
    protected RequestFactoryInterface $requestFactory;
    protected EventDispatcherInterface $eventDispatcher;
    protected EventFactoryInterface $eventFactory;
    protected MiddlewareRunnerFactoryInterface $runnerFactory;
    protected RouteHandlerInterface $routeHandler;
    protected EmitterInterface $emitter;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventFactory = $this->createMock(EventFactoryInterface::class);
        $this->runnerFactory = $this->createMock(MiddlewareRunnerFactoryInterface::class);
        $this->routeHandler = $this->createMock(RouteHandlerInterface::class);
        $this->emitter = $this->createMock(EmitterInterface::class);
    }

    /**
     * @throws Exception
     */
    public function testRunExecutesHttpFlow(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->requestFactory->expects($this->once())
            ->method('create')
            ->willReturn($request)
        ;

        $requestCreatedEvent = $this->createMock(RequestCreatedInterface::class);
        $onResponseEvent = $this->createMock(OnResponseInterface::class);

        $this->eventFactory->expects($this->once())
            ->method('requestCreated')
            ->with($request)
            ->willReturn($requestCreatedEvent)
        ;

        $this->eventFactory->expects($this->once())
            ->method('onResponse')
            ->with($response)
            ->willReturn($onResponseEvent)
        ;

        $matcher = $this->exactly(2);
        $this->eventDispatcher->expects($matcher)
            ->method('dispatch')
            ->willReturnCallback(
                function (object $object) use ($matcher, $requestCreatedEvent, $onResponseEvent) {
                    match ($matcher->numberOfInvocations()) {
                        1 => $this->assertEquals($requestCreatedEvent, $object),
                        2 => $this->assertEquals($onResponseEvent, $object),
                    };
                }
            )
        ;

        $runner = $this->createMock(MiddlewareRunnerInterface::class);
        $runner->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response)
        ;

        $this->runnerFactory->expects($this->once())
            ->method('create')
            ->with([ExceptionHandlerMiddlewareInterface::class], $this->isInstanceOf(RouteHandlerInterface::class))
            ->willReturn($runner)
        ;

        $this->emitter->expects($this->once())
            ->method('emit')
            ->with($response)
        ;

        $runner = new HttpApplicationRunner(
            $this->requestFactory,
            $this->eventDispatcher,
            $this->eventFactory,
            $this->runnerFactory,
            $this->routeHandler,
            $this->emitter
        );

        $runner->run();
    }
}
