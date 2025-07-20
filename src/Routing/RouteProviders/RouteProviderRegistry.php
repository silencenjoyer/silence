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

namespace Silence\Routing\RouteProviders;

use Silence\Routing\RouteGroupInterface;
use Silence\Routing\RouteProviderInterface;
use Silence\Routing\RouterInterface;

/**
 * Route registry.
 * An entity that allows client code to register routes in the application.
 */
final class RouteProviderRegistry
{
    private RouterInterface $router;
    /** @var list<RouteProviderInterface> */
    private array $providers = [];

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Add the route to the registry for further registration in the application.
     * Immutable.
     *
     * @param RouteProviderInterface $provider
     * @return $this
     */
    public function withRoute(RouteProviderInterface $provider): self
    {
        $clone = clone $this;
        $clone->providers[] = $provider;
        return $clone;
    }

    /**
     * Get all routes from the registry.
     *
     * @return list<RouteProviderInterface>
     */
    public function getAll(): array
    {
        return $this->providers;
    }

    /**
     * Registering routes in the router.
     *
     * @return self
     */
    public function register(): self
    {
        $routes = [];
        foreach ($this->providers as $provider) {
            foreach ($provider->getRoutes() as $route) {
                if ($route instanceof RouteGroupInterface) {
                    $routes = array_merge($routes, $route->getRoutes());
                } else {
                    $routes[] = $route;
                }
            }
        }
        $this->router->registerRoutes($routes);

        return $this;
    }
}
