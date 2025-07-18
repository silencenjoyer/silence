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

namespace Silence\Routing\Tests\Matcher;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Silence\Collection\BaseCollection;
use Silence\HttpSpec\HttpMethods\MethodEnum;
use Silence\Routing\HttpRoute;
use Silence\Routing\Matcher\HttpMatcher;
use Silence\Routing\RouteNotFound;

class HttpMatcherTest extends TestCase
{
    private UriInterface $uri;
    private ServerRequestInterface $request;
    private HttpRoute $route;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = $this->createMock(UriInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->route = $this->createMock(HttpRoute::class);
    }

    /**
     * @throws RouteNotFound
     * @throws Exception
     */
    public function testMatchReturnsMatchedRoute(): void
    {
        $path = '/test/123';

        $this->uri->method('getPath')->willReturn($path);

        $this->request->method('getUri')->willReturn($this->uri);
        $this->request->method('getMethod')->willReturn('GET');

        $this->route->method('getMethods')->willReturn([MethodEnum::GET]);
        $this->route->method('matchPath')->with($path)->willReturn(['id' => '123']);

        $collection = new BaseCollection();
        $collection->append($this->route);

        $matcher = (new HttpMatcher())->withRoutes($collection);

        $matched = $matcher->match($this->request);

        $this->assertSame(['id' => '123'], $matched->parameters);
        $this->assertSame($this->route, $matched->route);
    }

    /**
     * @throws Exception
     */
    public function testMatchThrowsIfNoRouteFound(): void
    {
        $path = '/not-found';

        $this->uri = $this->createMock(UriInterface::class);
        $this->uri->method('getPath')->willReturn($path);

        $this->request->method('getUri')->willReturn($this->uri);
        $this->request->method('getMethod')->willReturn('GET');

        $this->route->method('getMethods')->willReturn([MethodEnum::POST]);
        $this->route->method('matchPath')->with($path)->willReturn(null);

        $collection = new BaseCollection();
        $collection->append($this->route);

        $matcher = (new HttpMatcher())->withRoutes($collection);

        $this->expectException(RouteNotFound::class);

        $matcher->match($this->request);
    }
}
