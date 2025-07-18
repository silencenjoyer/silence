<?php

declare(strict_types=1);

namespace Silence\Event\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Event\NullEvent;
use Silence\Event\NullEventFactory;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\RouteInterface;

class NullEventFactoryTest extends TestCase
{
    private NullEventFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new NullEventFactory();
    }

    public function testKernelBootedCreatesNullEvent(): void
    {
        $this->assertInstanceOf(NullEvent::class, $this->factory->kernelBooted());
    }

    public function testBeforeKernelRunCreatesNullEvent(): void
    {
        $this->assertInstanceOf(NullEvent::class, $this->factory->beforeKernelRun());
    }

    /**
     * @throws Exception
     */
    public function testRequestCreatedCreatesNullEvent(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->assertInstanceOf(NullEvent::class, $this->factory->requestCreated($request));
    }

    /**
     * @throws Exception
     */
    public function testRouteResolvedCreatesNullEvent(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $route = new MatchedRoute($this->createMock(RouteInterface::class));

        $this->assertInstanceOf(NullEvent::class, $this->factory->routeResolved($route, $request));
    }

    /**
     * @throws Exception
     */
    public function testRouteNotFoundCreatesNullEvent(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->assertInstanceOf(NullEvent::class, $this->factory->routeNotFound($request));
    }

    /**
     * @throws Exception
     */
    public function testOnResponseCreatesNullEvent(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->assertInstanceOf(NullEvent::class, $this->factory->onResponse($response));
    }

    public function testKernelTerminatedCreatesNullEvent(): void
    {
        $this->assertInstanceOf(NullEvent::class, $this->factory->kernelTerminated());
    }
}
