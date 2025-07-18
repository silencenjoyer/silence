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

namespace Silence\Event\Realizations\Symfony;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Event\Realizations\Symfony\Types\BeforeKernelRun;
use Silence\Event\Realizations\Symfony\Types\KernelBooted;
use Silence\Event\Realizations\Symfony\Types\KernelTerminated;
use Silence\Event\Realizations\Symfony\Types\OnResponse;
use Silence\Event\Realizations\Symfony\Types\RequestCreated;
use Silence\Event\Realizations\Symfony\Types\RouteNotFound;
use Silence\Event\Realizations\Symfony\Types\RouteResolved;
use Silence\Event\Types\BeforeKernelRunInterface;
use Silence\Event\Types\KernelBootedInterface;
use Silence\Event\Types\KernelTerminatedInterface;
use Silence\Event\Types\OnResponseInterface;
use Silence\Event\Types\RequestCreatedInterface;
use Silence\Event\Types\RouteNotFoundInterface;
use Silence\Event\Types\RouteResolvedInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * Implementation of an event factory for the Symfony event dispatcher.
 */
final class SymfonyEventFactory implements EventFactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return KernelBootedInterface
     */
    public function kernelBooted(): KernelBootedInterface
    {
        return new KernelBooted();
    }

    /**
     * {@inheritDoc}
     *
     * @return BeforeKernelRunInterface
     */
    public function beforeKernelRun(): BeforeKernelRunInterface
    {
        return new BeforeKernelRun();
    }

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return RequestCreatedInterface
     */
    public function requestCreated(ServerRequestInterface $request): RequestCreatedInterface
    {
        return new RequestCreated($request);
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
        return new RouteResolved($route, $request);
    }

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return RouteNotFoundInterface
     */
    public function routeNotFound(ServerRequestInterface $request): RouteNotFoundInterface
    {
        return new RouteNotFound($request);
    }

    /**
     * {@inheritDoc}
     *
     * @param ResponseInterface $response
     * @return OnResponseInterface
     */
    public function onResponse(ResponseInterface $response): OnResponseInterface
    {
        return new OnResponse($response);
    }

    /**
     * {@inheritDoc}
     *
     * @return KernelTerminatedInterface
     */
    public function kernelTerminated(): KernelTerminatedInterface
    {
        return new KernelTerminated();
    }
}
