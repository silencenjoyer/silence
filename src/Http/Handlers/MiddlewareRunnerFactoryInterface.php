<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

namespace Silence\Http\Handlers;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * An interface for {@see MiddlewareRunner} factories.
 */
interface MiddlewareRunnerFactoryInterface
{
    /**
     * Must create an MiddlewareRunner instance.
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @param RequestHandlerInterface $finalHandler
     * @return MiddlewareRunner
     */
    public function create(array $middlewares, RequestHandlerInterface $finalHandler): MiddlewareRunnerInterface;
}
