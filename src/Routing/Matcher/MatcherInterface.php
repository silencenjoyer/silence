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

use Psr\Http\Message\ServerRequestInterface;
use Silence\Collection\BaseCollection;
use Silence\Routing\RouteNotFound;
use Silence\Routing\RouterInterface;

/**
 * Interface for processing suitable routes for the current request.
 */
interface MatcherInterface
{
    /**
     * The method must specify which routes will be used for resolution.
     * Immutable.
     * Must return a clone of current object.
     *
     * @param BaseCollection<array-key, RouterInterface> $routes
     */
    public function withRoutes(BaseCollection $routes): static;

    /**
     * The matching route for the request must be determined from the list of registered routes.
     *
     * @param ServerRequestInterface $request
     * @return MatchedRoute
     * @throws RouteNotFound
     */
    public function match(ServerRequestInterface $request): MatchedRoute;
}
