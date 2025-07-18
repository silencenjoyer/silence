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

use Psr\Http\Server\MiddlewareInterface;

/**
 * Route group interface.
 * A route group represents a list of related routes that are united by a path prefix or middlewares that must be applied.
 */
interface RouteGroupInterface
{
    /**
     * Must return a group identical to current one, but with the path prefix passed.
     * Must be immutable.
     *
     * @param string $prefix
     * @return static
     */
    public function withPrefix(string $prefix): static;

    /**
     * Must provide the path prefix of the current group.
     *
     * @return string
     */
    public function getPrefix(): string;

    /**
     * Must return a group identical to current one, but with the passed middlewares.
     *
     * @param list<class-string<MiddlewareInterface>>  $middlewares
     * @return static
     */
    public function withMiddlewares(array $middlewares): static;

    /**
     * Must return a list of middleware names that need to be applied to routes.
     *
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewares(): array;

    /**
     * Must return a list of routes that belong to the group.
     *
     * @return list<RouteInterface>
     */
    public function getRoutes(): array;
}
