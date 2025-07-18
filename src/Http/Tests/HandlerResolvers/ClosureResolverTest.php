<?php

declare(strict_types=1);

namespace Silence\Http\Tests\HandlerResolvers;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Silence\Http\HandlerResolvers\ClosureResolver;
use Silence\Http\Handlers\ClosureHandlerFactoryInterface;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\RouteInterface;
use stdClass;

class ClosureResolverTest extends TestCase
{
    private ContainerInterface $container;
    private ClosureHandlerFactoryInterface $handlerFactory;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->handlerFactory = $this->createMock(ClosureHandlerFactoryInterface::class);
    }

    protected function createClosureResolver(): ClosureResolver
    {
        return new ClosureResolver($this->container, $this->handlerFactory);
    }

    /**
     * @throws Exception
     */
    protected function createMatchedRoute(mixed $handler, array $params): MatchedRoute
    {
        $route = $this->createMock(RouteInterface::class);
        $route->method('getAction')
            ->willReturn($handler)
        ;
        return new MatchedRoute($route, $params);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function resolve(mixed $handler, array $params = []): void
    {
        $resolver = $this->createClosureResolver();
        $matchedRoute = $this->createMatchedRoute($handler, $params);

        $resolver->resolve($matchedRoute);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesClosureHandlerWithContainerDependency(): void
    {
        $service = $this->createMock(stdClass::class);

        $this->container
            ->method('has')
            ->with(stdClass::class)
            ->willReturn(true)
        ;
        $this->container
            ->method('get')
            ->with(stdClass::class)
            ->willReturn($service)
        ;

        $handler = function (stdClass $dependency): void {};

        $this->handlerFactory->method('create')->with($handler, [], ['dependency' => $service]);

        $this->resolve($handler);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesWithRouteParams(): void
    {
        $handler = function (string $company): void {};

        $this->handlerFactory->method('create')->with($handler, [], ['company' => 'Silence']);

        $this->resolve($handler, ['company' => 'Silence']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesWithUnusedRouteParams(): void
    {
        $handler = function (string $company): void {};

        $this->handlerFactory->method('create')->with($handler, [], ['company' => 'Silence']);

        $this->resolve($handler, ['company' => 'Silence', 'unused' => 'test']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesRequestObject(): void
    {
        $handler = function (ServerRequestInterface $request): void {};

        $this->handlerFactory->method('create')->with($handler, ['request'], []);

        $this->resolve($handler);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesWithRouteParamsAndRequestObject(): void
    {
        $handler = function (string $company, ServerRequestInterface $requestParam, int $int): void {};

        $this->handlerFactory
            ->method('create')
            ->with($handler, ['requestParam'], ['company' => 'Silence', 'int' => 3])
        ;

        $this->resolve($handler, ['company' => 'Silence', 'int' => 3]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesInvokableClass(): void
    {
        $stub = new class() {
            public function __invoke(): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub(...), [], []);

        $this->resolve($stub::class);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesInvokableClassWithRouteParams(): void
    {
        $stub = new class() {
            public function __invoke(string $company): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub(...), [], ['company' => 'Silence']);

        $this->resolve($stub::class, ['company' => 'Silence']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesInvokableClassWithUnusedRouteParams(): void
    {
        $stub = new class() {
            public function __invoke(string $company): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub(...), [], ['company' => 'Silence']);

        $this->resolve($stub::class, ['company' => 'Silence', 'unused' => 'test']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesInvokableClassRequestObject(): void
    {
        $stub = new class() {
            public function __invoke(ServerRequestInterface $request): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub(...), ['request'], []);

        $this->resolve($stub::class);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesInvokableClassWithRouteParamsAndRequestObject()
    {
        $stub = new class() {
            public function __invoke(string $company, ServerRequestInterface $requestParam, int $int): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory
            ->method('create')
            ->with($stub(...), ['requestParam'], ['company' => 'Silence', 'int' => 3])
        ;

        $this->resolve($stub::class, ['company' => 'Silence', 'int' => 3]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesClassMethodArray(): void
    {
        $stub = new class() {
            public function index(): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub->index(...), [], []);

        $this->resolve([$stub::class, 'index']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesClassMethodArrayWithRouteParams(): void
    {
        $stub = new class() {
            public function index(string $company): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub->index(...), [], ['company' => 'Silence']);

        $this->resolve([$stub::class, 'index'], ['company' => 'Silence']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testResolvesClassMethodArrayWithUnusedRouteParams(): void
    {
        $stub = new class() {
            public function index(string $company): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub->index(...), [], ['company' => 'Silence']);

        $this->resolve([$stub::class, 'index'], ['company' => 'Silence', 'unused' => 'test']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesClassMethodArrayRequestObject(): void
    {
        $stub = new class() {
            public function index(ServerRequestInterface $request): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory->method('create')->with($stub->index(...), ['request'], []);

        $this->resolve([$stub::class, 'index']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testResolvesClassMethodArrayWithRouteParamsAndRequestObject(): void
    {
        $stub = new class() {
            public function index(string $company, ServerRequestInterface $requestParam, int $int): void
            {
            }
        };

        $this->container->method('get')->with($stub::class)->willReturn($stub);

        $this->handlerFactory
            ->method('create')
            ->with($stub->index(...), ['requestParam'], ['company' => 'Silence', 'int' => 3])
        ;

        $this->resolve([$stub::class, 'index'], ['company' => 'Silence', 'int' => 3]);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function testThrowsOnInvalidHandler(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->resolve('test_unknown'); // not callable
    }
}
