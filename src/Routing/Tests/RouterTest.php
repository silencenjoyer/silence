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
use Psr\Http\Message\ServerRequestInterface;
use Silence\Collection\BaseCollection;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\Matcher\MatcherInterface;
use Silence\Routing\RouteNotFound;
use Silence\Routing\Router;

class RouterTest extends TestCase
{
    /**
     * @throws RouteNotFound
     * @throws Exception
     */
    public function testResolveDelegatesToMatcher(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $matchedRoute = $this->createMock(MatchedRoute::class);

        $matcher = $this->createMock(MatcherInterface::class);
        $matcher->expects($this->once())
            ->method('withRoutes')
            ->with($this->isInstanceOf(BaseCollection::class))
            ->willReturnSelf()
        ;

        $matcher->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($matchedRoute)
        ;

        $router = new Router($matcher);

        $this->assertSame($matchedRoute, $router->resolve($request));
    }
}
