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

namespace Silence\Routing;

use ArrayObject;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Collection\BaseCollection;
use Silence\Routing\Matcher\MatchedRoute;
use Silence\Routing\Matcher\MatcherInterface;

/**
 * Implementation of a router for determining the current action.
 */
readonly class Router implements RouterInterface
{
    /**
     * @var BaseCollection<string, ArrayObject<int, RouteInterface>> $routes
     */
    private BaseCollection $routes;
    private MatcherInterface $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;

        $this->routes = new BaseCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @param list<RouteInterface> $routes
     * @return static
     */
    public function registerRoutes(array $routes): static
    {
        foreach ($routes as $route) {
            $this->registerRoute($route);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param RouteInterface $route
     * @return $this
     */
    public function registerRoute(RouteInterface $route): static
    {
        $this->routes->append($route);
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return MatchedRoute
     * @throws RouteNotFound
     */
    public function resolve(ServerRequestInterface $request): MatchedRoute
    {
        return $this->matcher->withRoutes($this->routes)->match($request);
    }
}
