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

use Closure;
use Silence\HttpSpec\HttpMethods\MethodEnum;
use Psr\Http\Server\MiddlewareInterface;

/**
 * The route interface, which declares all common features for all routes.
 */
interface RouteInterface
{
    /**
     * Register the route name. Associate the route with a specific name.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): static;

    /**
     * Must return a list of HTTP methods supported by this route.
     *
     * @return list<MethodEnum>
     */
    public function getMethods(): array;

    /**
     * Must return the name of the route with which it is associated.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Must return a route identical to the current one, but with registered path prefix.
     * Must be Immutable.
     *
     * @param string $prefix
     * @return $this
     */
    public function withPathPrefix(string $prefix): static;

    /**
     * Must return the path associated with this route.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * This method must try to determine whether the route is associated with the provided path.
     * Should return the list of route parameters if successful, or null if the path is not suitable.
     *
     * @param string $path
     * @return array<string, mixed>|null route parameters in case of success, null in case of failure.
     */
    public function matchPath(string $path): ?array;

    /**
     * Method must return the handler for this route. The function (controller) that is registered behind it.
     *
     * @return Closure|array<class-string, string>|string
     */
    public function getAction(): Closure|array|string;

    /**
     * Must return a route identical to the current one, but with registered middlewares.
     * Must be Immutable.
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @return RouteInterface
     */
    public function withMiddlewares(array $middlewares): RouteInterface;

    /**
     * Must return a list of middlewares related to this route.
     *
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewares(): array;
}
