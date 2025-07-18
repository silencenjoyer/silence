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
 * The event factory interface.
 *
 * PSR-14 describes the interfaces for using the event dispatcher, but the configuration of the dispatcher depends on
 * specific implementation.
 *
 * Often, implementation is linked to specific event registration and processing mechanisms that require event objects
 * known to the current package.
 *
 * The event factory allows you to replace event objects around the kernel with those that the current implementation
 * of the dispatcher can work with.
 */
interface EventFactoryInterface
{
    /**
     * Creating an event that signals the start of the kernel.
     *
     * @return KernelBootedInterface
     */
    public function kernelBooted(): KernelBootedInterface;

    /**
     * Creating an event that signals the launch of the application.
     *
     * @return BeforeKernelRunInterface
     */
    public function beforeKernelRun(): BeforeKernelRunInterface;

    /**
     * Creating an event that signals the creation of a server request.
     *
     * @param ServerRequestInterface $request
     * @return RequestCreatedInterface
     */
    public function requestCreated(ServerRequestInterface $request): RequestCreatedInterface;

    /**
     * Creating an event that signals a successfully resolved route.
     *
     * @param MatchedRoute $route
     * @param ServerRequestInterface $request
     * @return RouteResolvedInterface
     */
    public function routeResolved(MatchedRoute $route, ServerRequestInterface $request): RouteResolvedInterface;

    /**
     * Creating an event that signals a failed attempt to resolve a route.
     *
     * @param ServerRequestInterface $request
     * @return RouteNotFoundInterface
     */
    public function routeNotFound(ServerRequestInterface $request): RouteNotFoundInterface;

    /**
     * Creating an event that signals a response has been generated for the client.
     *
     * @param ResponseInterface $response
     * @return OnResponseInterface
     */
    public function onResponse(ResponseInterface $response): OnResponseInterface;

    /**
     * Creating an event that signals terminate of the kernel.
     *
     * @return KernelTerminatedInterface
     */
    public function kernelTerminated(): KernelTerminatedInterface;
}
