<?php

declare(strict_types=1);

namespace Silence\Event\Realizations\Symfony\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Event\Realizations\Symfony\SymfonyEventFactory;
use Silence\Event\Realizations\Symfony\Types\BeforeKernelRun;
use Silence\Event\Realizations\Symfony\Types\KernelBooted;
use Silence\Event\Realizations\Symfony\Types\KernelTerminated;
use Silence\Event\Realizations\Symfony\Types\OnResponse;
use Silence\Event\Realizations\Symfony\Types\RequestCreated;
use Silence\Event\Realizations\Symfony\Types\RouteNotFound;
use Silence\Event\Realizations\Symfony\Types\RouteResolved;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\RouteInterface;

class SymfonyEventFactoryTest extends TestCase
{
    private SymfonyEventFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new SymfonyEventFactory();
    }

    public function testKernelBootedCreates(): void
    {
        $this->assertInstanceOf(KernelBooted::class, $this->factory->kernelBooted());
    }

    public function testBeforeKernelRunCreates(): void
    {
        $this->assertInstanceOf(BeforeKernelRun::class, $this->factory->beforeKernelRun());
    }

    /**
     * @throws Exception
     */
    public function testRequestCreatedCreates(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->assertInstanceOf(RequestCreated::class, $this->factory->requestCreated($request));
    }

    /**
     * @throws Exception
     */
    public function testRouteResolvedCreates(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $route = new MatchedRoute($this->createMock(RouteInterface::class));

        $this->assertInstanceOf(RouteResolved::class, $this->factory->routeResolved($route, $request));
    }

    /**
     * @throws Exception
     */
    public function testRouteNotFoundCreates(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $this->assertInstanceOf(RouteNotFound::class, $this->factory->routeNotFound($request));
    }

    /**
     * @throws Exception
     */
    public function testOnResponseCreates(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->assertInstanceOf(OnResponse::class, $this->factory->onResponse($response));
    }

    public function testKernelTerminatedCreates(): void
    {
        $this->assertInstanceOf(KernelTerminated::class, $this->factory->kernelTerminated());
    }
}
