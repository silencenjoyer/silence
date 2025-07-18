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

namespace Silence\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Event\Types\BeforeKernelRunInterface;
use Silence\Event\Types\KernelBootedInterface;
use Silence\Event\Types\KernelTerminatedInterface;
use Silence\Event\Types\OnResponseInterface;
use Silence\Event\Types\RequestCreatedInterface;
use Silence\Event\Types\RouteNotFoundInterface;
use Silence\Event\Types\RouteResolvedInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * Reserve event factory.
 *
 * Used if no event handler is registered. Does not imply any event processing and uses a fake event object
 * {@see NullEvent}.
 */
final class NullEventFactory implements EventFactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return KernelBootedInterface
     */
    public function kernelBooted(): KernelBootedInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @return BeforeKernelRunInterface
     */
    public function beforeKernelRun(): BeforeKernelRunInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return RequestCreatedInterface
     */
    public function requestCreated(ServerRequestInterface $request): RequestCreatedInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @param MatchedRoute $route
     * @param ServerRequestInterface $request
     * @return RouteResolvedInterface
     */
    public function routeResolved(MatchedRoute $route, ServerRequestInterface $request): RouteResolvedInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return RouteNotFoundInterface
     */
    public function routeNotFound(ServerRequestInterface $request): RouteNotFoundInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @param ResponseInterface $response
     * @return OnResponseInterface
     */
    public function onResponse(ResponseInterface $response): OnResponseInterface
    {
        return new NullEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @return KernelTerminatedInterface
     */
    public function kernelTerminated(): KernelTerminatedInterface
    {
        return new NullEvent();
    }
}
