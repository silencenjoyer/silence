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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This is a class used to start a chain of middlewares.
 *
 * It waits for a list of middlewares that need to be executed before the final handler.
 *
 * It forms a call stack for further processing of the HTTP request.
 *
 * Usage example:
 *
 * ```
 * $request = ...;
 * $handler = ...;
 * $middlewares = [
 *     SessionMiddleware::class,
 *     LocaleMiddleware::class,
 *     AuthMiddleware::class,
 * ];
 * $runner = new MiddlewareRunner($middlewares, $handler);
 * $runner->handle($request);
 * ```
 */
final class MiddlewareRunner implements MiddlewareRunnerInterface
{
    /** @var list<MiddlewareInterface> */
    private array $middlewares;
    /** @var RequestHandlerInterface */
    private RequestHandlerInterface $finalHandler;

    /**
     * @param list<MiddlewareInterface> $middlewares
     * @param RequestHandlerInterface $finalHandler
     */
    public function __construct(array $middlewares, RequestHandlerInterface $finalHandler)
    {
        $this->middlewares = $middlewares;
        $this->finalHandler = $finalHandler;
    }

    /**
     * Handles the incoming HTTP request by forming and executing a middleware stack.
     *
     * Each middleware can process the request and either return a response or delegate it further down the stack.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middlewares === []) {
            return $this->finalHandler->handle($request);
        }

        $middleware = array_shift($this->middlewares);

        return $middleware->process($request, new self($this->middlewares, $this->finalHandler));
    }
}
