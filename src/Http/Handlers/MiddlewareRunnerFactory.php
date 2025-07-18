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

namespace Silence\Http\Handlers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Factory for creating {@see MiddlewareRunner}.
 *
 * Encapsulates the logic of creating the runner itself, as well as creating middlewares using a DI container.
 */
final readonly class MiddlewareRunnerFactory implements MiddlewareRunnerFactoryInterface
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * Assembling middlewares using a DI container.
     *
     * Middleware may have dependencies on components that will be automatically resolved.
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @return list<MiddlewareInterface>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function build(array $middlewares): array
    {
        $result = [];
        foreach ($middlewares as $middleware) {
            $middleware = $this->container->get($middleware);

            if ($middleware instanceof MiddlewareInterface) {
                $result[] = $middleware;
            }
        }

        return $result;
    }

    /**
     * Creates a runner instance, builds middlewares.
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @param RequestHandlerInterface $finalHandler
     * @return MiddlewareRunner
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(array $middlewares, RequestHandlerInterface $finalHandler): MiddlewareRunnerInterface
    {
        return new MiddlewareRunner($this->build($middlewares), $finalHandler);
    }
}
