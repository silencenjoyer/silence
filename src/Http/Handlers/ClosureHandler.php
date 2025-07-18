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

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Handler that transfers execution to the final client route handler.
 *
 * This could be a specific controller or another handler assigned to the route.
 * This will be the action in your application that will be executed when the specified path or URL is accessed.
 */
class ClosureHandler implements RequestHandlerInterface
{
    /**
     * Final route handler.
     *
     * @var Closure(): ResponseInterface
     */
    private Closure $action;
    /**
     * Parameters that need to be passed to the route handler function.
     *
     * @var array<array-key, mixed>
     */
    private array $params;
    /**
     * The names of the parameters to which the HTTP request should be provided.
     *
     * @var list<string>
     */
    private array $requestParameters;

    /**
     * @param Closure(): ResponseInterface $action
     * @param list<string> $requestParameters
     * @param array<array-key, mixed> $params
     */
    public function __construct(Closure $action, array $requestParameters, array $params = [])
    {
        $this->action = $action;
        $this->requestParameters = $requestParameters;
        $this->params = $params;
    }

    /**
     * Provides an instance of an HTTP request object by the names of the specified parameters, if necessary.
     * Passes execution directly to the final route handler.
     *
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->requestParameters as $paramName) {
            $this->params[$paramName] = $request;
        }

        return ($this->action)(...$this->params);
    }
}
