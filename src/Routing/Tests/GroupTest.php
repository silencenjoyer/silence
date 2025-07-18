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

namespace Silence\Routing\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use Silence\Routing\Group;
use Silence\Routing\RouteGroupInterface;
use Silence\Routing\RouteInterface;

class GroupTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testOfCreatesGroupWithGivenRoutes(): void
    {
        $route1 = $this->createMock(RouteInterface::class);
        $route2 = $this->createMock(RouteInterface::class);

        $group = Group::of([$route1, $route2]);

        $routes = $group->getRoutes();

        $this->assertCount(2, $routes);
    }

    public function testWithPrefixReturnsNewInstance(): void
    {
        $group = new Group();
        $newGroup = $group->withPrefix('/admin');

        $this->assertNotSame($group, $newGroup);
        $this->assertSame('/admin', $newGroup->getPrefix());
        $this->assertSame('', $group->getPrefix());
    }

    /**
     * @throws Exception
     */
    public function testWithMiddlewaresReturnsNewInstance(): void
    {
        $group = new Group();

        $middleware = $this->createMock(MiddlewareInterface::class);
        $newGroup = $group->withMiddlewares([$middleware::class]);

        $this->assertNotSame($group, $newGroup);
        $this->assertSame([$middleware::class], $newGroup->getMiddlewares());
        $this->assertSame([], $group->getMiddlewares());
    }

    /**
     * @throws Exception
     */
    public function testGetRoutesAppliesPrefixAndMiddlewares(): void
    {
        $middleware = $this->createMock(MiddlewareInterface::class)::class;
        $prefix = '/api';

        $route1 = $this->createMock(RouteInterface::class);
        $route1->method('withMiddlewares')->with([$middleware])->willReturnSelf();
        $route1->method('withPathPrefix')->with($prefix)->willReturnSelf();

        $group = Group::of([$route1])
            ->withPrefix($prefix)
            ->withMiddlewares([$middleware])
        ;

        $routes = $group->getRoutes();

        $this->assertSame([$route1], $routes);
    }

    /**
     * @throws Exception
     */
    public function testGetRoutesHandlesNestedGroups(): void
    {
        $middleware = $this->createMock(MiddlewareInterface::class)::class;
        $nestedMiddleware = $this->createMock(MiddlewareInterface::class)::class;

        $prefix = '/v1';

        $nestedRoute = $this->createMock(RouteInterface::class);
        $nestedRoute->method('getMiddlewares')->willReturn([$nestedMiddleware]);
        $nestedRoute->method('withMiddlewares')->with([$middleware, $nestedMiddleware])->willReturnSelf();
        $nestedRoute->method('withPathPrefix')->with($prefix)->willReturnSelf();

        $nestedGroup = $this->createMock(RouteGroupInterface::class);
        $nestedGroup->method('getRoutes')->willReturn([$nestedRoute]);

        $group = Group::of([$nestedGroup])
            ->withPrefix($prefix)
            ->withMiddlewares([$middleware])
        ;

        $routes = $group->getRoutes();

        $this->assertSame([$nestedRoute], $routes);
    }
}
