<?php

declare(strict_types=1);

namespace Silence\Http\Tests\Handlers;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Event\Types\RouteNotFoundInterface;
use Silence\Event\Types\RouteResolvedInterface;
use Silence\Http\HandlerResolvers\HandlerResolverInterface;
use Silence\Http\Handlers\MiddlewareRunnerFactoryInterface;
use Silence\Http\Handlers\MiddlewareRunnerInterface;
use Silence\Http\Handlers\RouteHandler;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\RouteInterface;
use Silence\Routing\RouteNotFound;
use Silence\Routing\RouterInterface;

class RouteHandlerTest extends TestCase
{
    private RouterInterface $router;
    private HandlerResolverInterface $resolver;
    private MiddlewareRunnerFactoryInterface $runnerFactory;
    private RequestHandlerInterface $fallback;
    private EventDispatcherInterface $dispatcher;
    private EventFactoryInterface $eventFactory;
    private ServerRequestInterface $request;
    private ResponseInterface $response;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->resolver = $this->createMock(HandlerResolverInterface::class);
        $this->runnerFactory = $this->createMock(MiddlewareRunnerFactoryInterface::class);
        $this->fallback = $this->createMock(RequestHandlerInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventFactory = $this->createMock(EventFactoryInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    /**
     * @throws Exception
     */
    public function testHandleSuccessfullyResolvedRoute(): void
    {
        $route = $this->createMock(RouteInterface::class);

        $route->method('getMiddlewares')->willReturn([]);

        $resolved = new MatchedRoute($route, ['id' => 42]);

        $this->router
            ->expects($this->once())
            ->method('resolve')
            ->with($this->request)
            ->willReturn($resolved)
        ;

        $handler = $this->createMock(MiddlewareRunnerInterface::class);
        $this->resolver
            ->expects($this->once())
            ->method('resolve')
            ->with($resolved)
            ->willReturn($handler)
        ;

        $requestWithAttr = $this->createMock(ServerRequestInterface::class);
        $this->request->method('withAttribute')->with('id', 42)->willReturn($requestWithAttr);

        $routeResolvedEvent = $this->createMock(RouteResolvedInterface::class);
        $this->eventFactory
            ->expects($this->once())
            ->method('routeResolved')
            ->with($resolved, $requestWithAttr)
            ->willReturn($routeResolvedEvent)
        ;

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($routeResolvedEvent)
        ;

        $this->runnerFactory
            ->expects($this->once())
            ->method('create')
            ->with([], $handler)
            ->willReturn($handler)
        ;

        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($requestWithAttr)
            ->willReturn($this->response)
        ;

        $routeHandler = new RouteHandler(
            $this->router,
            $this->resolver,
            $this->runnerFactory,
            $this->fallback,
            $this->dispatcher,
            $this->eventFactory
        );

        $result = $routeHandler->handle($this->request);

        $this->assertSame($this->response, $result);
    }

    /**
     * @throws Exception
     */
    public function testHandleRouteNotFoundTriggersFallback(): void
    {
        $this->router
            ->expects($this->once())
            ->method('resolve')
            ->with($this->request)
            ->willThrowException(new RouteNotFound())
        ;

        $routeNotFoundEvent = $this->createMock(RouteNotFoundInterface::class);
        $this->eventFactory
            ->expects($this->once())
            ->method('routeNotFound')
            ->with($this->request)
            ->willReturn($routeNotFoundEvent)
        ;

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($routeNotFoundEvent)
        ;

        $this->fallback
            ->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($this->response)
        ;

        $routeHandler = new RouteHandler(
            $this->router,
            $this->resolver,
            $this->runnerFactory,
            $this->fallback,
            $this->dispatcher,
            $this->eventFactory
        );

        $result = $routeHandler->handle($this->request);

        $this->assertSame($this->response, $result);
    }
}
