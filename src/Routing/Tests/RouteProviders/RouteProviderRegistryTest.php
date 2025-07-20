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

namespace Silence\Routing\Tests\RouteProviders;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Silence\Routing\RouteGroupInterface;
use Silence\Routing\RouteProviderInterface;
use Silence\Routing\RouteProviders\RouteProviderRegistry;
use Silence\Routing\RouterInterface;

class RouteProviderRegistryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWithRouteAddsProviderImmutably(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $provider = $this->createMock(RouteProviderInterface::class);

        $registry = new RouteProviderRegistry($router);
        $newRegistry = $registry->withRoute($provider);

        $this->assertNotSame($registry, $newRegistry);
        $this->assertEmpty($registry->getAll());
        $this->assertSame([$provider], $newRegistry->getAll());
    }

    /**
     * @throws Exception
     */
    public function testGetAllReturnsProviders(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $provider1 = $this->createMock(RouteProviderInterface::class);
        $provider2 = $this->createMock(RouteProviderInterface::class);

        $registry = (new RouteProviderRegistry($router))
            ->withRoute($provider1)
            ->withRoute($provider2)
        ;

        $this->assertSame([$provider1, $provider2], $registry->getAll());
    }

    /**
     * @throws Exception
     */
    public function testRegisterRegistersAllRoutes(): void
    {
        $route1 = $this->createMock(RouterInterface::class);
        $route2 = $this->createMock(RouterInterface::class);
        $route3 = $this->createMock(RouterInterface::class);

        $group = $this->createMock(RouteGroupInterface::class);
        $group->method('getRoutes')->willReturn([$route2, $route3]);

        $provider = $this->createMock(RouteProviderInterface::class);
        $provider->method('getRoutes')->willReturn([$route1, $group]);

        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('registerRoutes')
            ->with([$route1, $route2, $route3])
        ;

        $registry = (new RouteProviderRegistry($router))
            ->withRoute($provider)
        ;

        $registry->register();
    }
}
