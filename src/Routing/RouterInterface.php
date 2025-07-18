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

use Psr\Http\Message\ServerRequestInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * Router interface for storing and determining the current route based on an incoming server request.
 */
interface RouterInterface
{
    /**
     * Registration of a route for storage and further processing.
     *
     * @param RouteInterface $route
     * @return $this
     */
    public function registerRoute(RouteInterface $route): static;

    /**
     * Registration of a list of routes for storage and further processing.
     *
     * @param list<RouteInterface> $routes
     * @return $this
     */
    public function registerRoutes(array $routes): static;

    /**
     * Route resolution should occur based on the incoming request.
     *
     * @param ServerRequestInterface $request
     * @return MatchedRoute
     * @throws RouteNotFound
     */
    public function resolve(ServerRequestInterface $request): MatchedRoute;
}
