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

class Group implements RouteGroupInterface
{
    private string $prefix = '';
    /**
     * @var list<RouteInterface|RouteGroupInterface>
     */
    private array $routes = [];
    /**
     * @var list<class-string<MiddlewareInterface>>
     */
    private array $middlewares = [];

    /**
     * {@inheritDoc}
     *
     * @param string $prefix
     * @return $this
     */
    public function withPrefix(string $prefix): static
    {
        $clone = clone $this;
        $clone->prefix = $prefix;
        return $clone;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * A static method to create a group for a list of routes.
     * For example:
     * ```
     * Silence\Routing\Group::of([
     *      Silence\Routing\HttpRoute::get('/', [SiteController::class, 'homePage']),
     *      Silence\Routing\HttpRoute::get('/about', [SiteController::class, 'about']),
     *      Silence\Routing\HttpRoute::get('/contact', [SiteController::class, 'contact']),
     * ])->withMiddlewares([SomeMiddleware::class]),
     * ```
     *
     * @param list<RouteInterface> $routes
     * @return self
     */
    public static function of(array $routes): self
    {
        $instance = new self();
        $instance->routes = $routes;
        return $instance;
    }

    /**
     * {@inheritDoc}
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @return $this
     */
    public function withMiddlewares(array $middlewares): static
    {
        $clone = clone $this;
        $clone->middlewares = $middlewares;
        return $clone;
    }

    /**
     * {@inheritDoc}
     *
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Returns a list of routes for this group, with the prefix and middlewares applied.
     * Goes through the list of routes, applying changes.
     *
     * If a nested group is detected, the algorithm is applied to its contents at any level of nesting in the same way.
     * The result is then added into the list of ready routes.
     *
     * @return array|RouteInterface[]
     */
    public function getRoutes(): array
    {
        $middlewares = $this->getMiddlewares();

        $groupRoutes = [];
        foreach ($this->routes as $route) {
            if ($route instanceof RouteGroupInterface) {
                foreach ($route->getRoutes() as $nestedRoute) {
                    $groupRoutes[] = $nestedRoute
                        ->withMiddlewares([...$middlewares, ...$nestedRoute->getMiddlewares()])
                        ->withPathPrefix($this->getPrefix())
                    ;
                }
            } else {
                $groupRoutes[] = $route
                    ->withMiddlewares($middlewares)
                    ->withPathPrefix($this->getPrefix())
                ;
            }
        }

        return $groupRoutes;
    }
}
