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

use ArrayObject;
use Closure;
use Silence\HttpSpec\HttpMethods\MethodEnum;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Implementation of HTTP route.
 *
 * @phpstan-consistent-constructor
 */
class HttpRoute implements RouteInterface
{
    /**
     * @var ArrayObject<array-key, MethodEnum> $methods
     */
    private ArrayObject $methods;
    private ?string $name = null;
    private string $path;
    /**
     * @var Closure|array<class-string, string>|string
     */
    private Closure|array|string $action;
    /** @var list<class-string<MiddlewareInterface>> */
    private array $middlewares = [];

    /**
     * Path setter.
     *
     * Slashes processing.
     *
     * @param string $path
     * @return void
     */
    private function setPath(string $path): void
    {
        $this->path = sprintf('%\'/1s', rtrim($path, '/')); // at least '/', non-empty
    }

    /**
     * @param list<MethodEnum> $methods
     * @param string $path
     * @param Closure|array<class-string, string>|string $action
     */
    public function __construct(array $methods, string $path, Closure|array|string $action)
    {
        $this->methods = new ArrayObject($methods);
        $this->setPath($path);
        $this->action = $action;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<MethodEnum>
     */
    public function getMethods(): array
    {
        return $this->methods->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $path
     * @return array<string, mixed>|null
     */
    public function matchPath(string $path): ?array
    {
        $pattern = preg_replace('#{(\w+)}#', '(?P<$1>[^/]+)', $this->path);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches)) {
            return array_filter($matches, is_string(...), ARRAY_FILTER_USE_KEY);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return Closure|array<class-string, string>|string
     */
    public function getAction(): Closure|array|string
    {
        return $this->action;
    }

    /**
     * Registration of route according to the provided HTTP methods.
     *
     * @param list<MethodEnum> $methods List of associated HTTP methods.
     * @param string $pattern Url pattern.
     * @param Closure|array<class-string, string>|string $action Handler, action (controller).
     * @return static
     */
    public static function methods(array $methods, string $pattern, Closure|array|string $action): static
    {
        return new static($methods, $pattern, $action);
    }

    /**
     * Registering a route with a binding to the HTTP GET method.
     *
     * @param string $pattern
     * @param Closure|array<class-string, string>|string $action
     * @return static
     */
    public static function get(string $pattern, Closure|array|string $action): static
    {
        return self::methods([MethodEnum::GET], $pattern, $action);
    }

    /**
     * Registering a route with a binding to the HTTP POST method.
     *
     * @param string $pattern
     * @param Closure|array<class-string, string>|string $action
     * @return static
     */
    public static function post(string $pattern, Closure|array|string $action): static
    {
        return self::methods([MethodEnum::POST], $pattern, $action);
    }

    /**
     * {@inheritDoc}
     *
     * @param list<class-string<MiddlewareInterface>> $middlewares
     * @return RouteInterface
     */
    public function withMiddlewares(array $middlewares): RouteInterface
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
     * {@inheritDoc}
     *
     * @param string $prefix
     * @return $this
     */
    public function withPathPrefix(string $prefix): static
    {
        $clone = clone $this;
        $clone->setPath(str_replace('//', '/', $prefix . $clone->path));
        return $clone;
    }
}
