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

use Closure;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use Silence\HttpSpec\HttpMethods\MethodEnum;
use Silence\Routing\HttpRoute;

class HttpRouteTest extends TestCase
{
    public function testConstructSetsPathAndMethods(): void
    {
        $route = new HttpRoute([MethodEnum::GET, MethodEnum::POST], '/api', 'handler');

        $this->assertSame('/api', $route->getPath());
        $this->assertEquals([MethodEnum::GET, MethodEnum::POST], $route->getMethods());
        $this->assertSame('handler', $route->getAction());
    }

    public function testGetCreatesRouteWithGetMethod(): void
    {
        $route = HttpRoute::get('/test', 'controller@action');

        $this->assertSame('/test', $route->getPath());
        $this->assertEquals([MethodEnum::GET], $route->getMethods());
        $this->assertSame('controller@action', $route->getAction());
    }

    public function testPostCreatesRouteWithPostMethod(): void
    {
        $route = HttpRoute::post('/submit', fn (): string => 'done');

        $this->assertSame('/submit', $route->getPath());
        $this->assertEquals([MethodEnum::POST], $route->getMethods());
        $this->assertInstanceOf(Closure::class, $route->getAction());
    }

    public function testNameSetsAndReturnsRouteName(): void
    {
        $route = HttpRoute::get('/page', 'view')->name('page.view');

        $this->assertSame('page.view', $route->getName());
    }

    public function testMatchPathReturnsParams(): void
    {
        $route = HttpRoute::get('/user/{id}/post/{postId}', 'handler');

        $match = $route->matchPath('/user/123/post/456');

        $this->assertSame(['id' => '123', 'postId' => '456'], $match);
    }

    public function testMatchPathReturnsNullIfNoMatch(): void
    {
        $route = HttpRoute::get('/user/{id}', 'handler');

        $this->assertNull($route->matchPath('/wrong/path'));
    }

    public function testWithMiddlewaresReturnsNewInstance(): void
    {
        $middleware = $this->createMock(MiddlewareInterface::class);

        $route = HttpRoute::get('/secure', 'handler');
        $new = $route->withMiddlewares([$middleware::class]);

        $this->assertNotSame($route, $new);
        $this->assertSame([$middleware::class], $new->getMiddlewares());
        $this->assertSame([], $route->getMiddlewares());
    }

    public function testWithPathPrefixPrependsPrefix(): void
    {
        $route = HttpRoute::get('/profile', 'handler');
        $new = $route->withPathPrefix('/api/v1');

        $this->assertNotSame($route, $new);
        $this->assertSame('/api/v1/profile', $new->getPath());
        $this->assertSame('/profile', $route->getPath()); // оригинал не тронут
    }

    public function testSetPathNormalizesTrailingSlash(): void
    {
        $route = HttpRoute::get('/test/', 'handler');

        $this->assertSame('/test', $route->getPath());
    }

    public function testEmptyPathBecomesSlash(): void
    {
        $route = HttpRoute::get('', 'handler');

        $this->assertSame('/', $route->getPath());
    }
}
