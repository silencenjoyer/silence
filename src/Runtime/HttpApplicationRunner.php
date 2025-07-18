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

namespace Silence\Runtime;

use Psr\EventDispatcher\EventDispatcherInterface;
use Silence\ErrorHandler\Middlewares\ExceptionHandlerMiddlewareInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Http\Emitters\EmitterInterface;
use Silence\Http\Handlers\MiddlewareRunnerFactoryInterface;
use Silence\Http\Handlers\RouteHandlerInterface;
use Silence\Http\Request\RequestFactoryInterface;

/**
 * Describes the algorithm for launching an HTTP application.
 *
 * It includes:
 *  - Creating an HTTP server request
 *  - Event dispatching
 *  - Launching middlewareRunner with PSR-15 HTTP handler chain execution
 *  - Returning HTTP headers and response content
 */
class HttpApplicationRunner implements ApplicationRunnerInterface
{
    public function __construct(
        protected RequestFactoryInterface $requestFactory,
        protected EventDispatcherInterface $eventDispatcher,
        protected EventFactoryInterface $eventFactory,
        protected MiddlewareRunnerFactoryInterface $middlewareRunnerFactory,
        protected RouteHandlerInterface $routeHandler,
        protected EmitterInterface $emitter,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function run(): void
    {
        $request = $this->requestFactory->create();

        $this->eventDispatcher->dispatch($this->eventFactory->requestCreated($request));

        $runner = $this->middlewareRunnerFactory->create([ExceptionHandlerMiddlewareInterface::class], $this->routeHandler);
        $response = $runner->handle($request);

        $this->eventDispatcher->dispatch($this->eventFactory->onResponse($response));

        $this->emitter->emit($response);
    }
}
