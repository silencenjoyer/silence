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

namespace Silence\Routing\Matcher;

use ArrayObject;
use Silence\HttpSpec\HttpMethods\MethodEnum;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Collection\BaseCollection;
use Silence\Routing\HttpRoute;
use Silence\Routing\RouteInterface;
use Silence\Routing\RouteNotFound;
use Silence\Routing\RouterInterface;

/**
 * Implementation of the HTTP route matcher.
 */
class HttpMatcher implements MatcherInterface
{
    /**
     * @var BaseCollection<string, ArrayObject<int, HttpRoute>> $routes
     */
    private BaseCollection $routes;

    public function __construct()
    {
        $this->routes = new BaseCollection();

        foreach (MethodEnum::cases() as $method) {
            $this->routes->set($method->value, new ArrayObject());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param BaseCollection<array-key, RouterInterface> $routes
     * @return static
     */
    public function withRoutes(BaseCollection $routes): static
    {
        $clone = clone $this;

        /** @var HttpRoute $route */
        foreach ($routes as $route) {
            foreach ($route->getMethods() as $method) {
                $clone->routes->get($method->value)->append($route);
            }
        }

        return $clone;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RouteNotFound
     */
    public function match(ServerRequestInterface $request): MatchedRoute
    {
        $path = $request->getUri()->getPath();

        /** @var RouteInterface $route */
        foreach ($this->routes->get($request->getMethod()) as $route) {

            if (($params = $route->matchPath($path)) === null) {
                continue;
            }

            return new MatchedRoute($route, $params);
        }

        throw new RouteNotFound();
    }
}
