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

namespace Silence\Http\HandlerResolvers;

use Closure;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use Silence\Http\Handlers\ClosureHandler;
use Silence\Http\Handlers\ClosureHandlerFactoryInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * Final request processor handler.
 *
 * Works with it as a Closure object.
 * It has basic functionality for resolving dependencies and function input parameters.
 *
 * If a controller is registered as a handler, it will create an instance of it using a DI container that resolves the
 * constructor dependencies.
 * ```
 * class Controller
 * {
 *      public function __construct(
 *          private AppConfig $config,
 *          private ViewRendererInterface $viewRenderer
 *      ) {
 *      }
 * }
 * ```
 *
 * Also, it will try to resolve the input parameters of the handler function.
 * ```
 * class Controller
 * {
 *      public function home(ViewRendererInterface $viewRenderer): ResponseInterface
 *      {
 *          return $viewRenderer->render('site/home.html.twig');
 *      }
 * }
 * ```
 */
class ClosureResolver implements HandlerResolverInterface
{
    private ContainerInterface $container;
    private ClosureHandlerFactoryInterface $handlerFactory;

    public function __construct(ContainerInterface $container, ClosureHandlerFactoryInterface $factory)
    {
        $this->container = $container;
        $this->handlerFactory = $factory;
    }

    /**
     * Resolves input parameters for the handler function.
     *
     * This method allows you to request parameters in a function, as in this example:
     * ```
     *  class Controller
     *  {
     *       public function home(ViewRendererInterface $viewRenderer): ResponseInterface
     *       {
     *           return $viewRenderer->render('site/home.html.twig');
     *       }
     *  }
     * ```
     *
     * @param Closure $handler
     * @param MatchedRoute $resolved
     * @return array{array<non-empty-string, mixed>, list<non-empty-string>}
     *     Returns an array of 2 elements:
     *     1 - resolved function parameters.
     *     2 - the name of the parameters that the HTTP-request object must contain.
     *     It cannot be obtained from the DI container because it is immutable, so it must be provided by the
     *     {@see ClosureHandler}, which has access to the most current instance.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    private function resolveParameters(Closure $handler, MatchedRoute $resolved): array
    {
        $requestParameters = []; // If the handler requests a HTTP-request, it cannot be provided from the container.
        $parameters = [];
        $reflection = new ReflectionFunction($handler);

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type instanceof ReflectionNamedType) {
                $type = null;
            }

            if (isset($resolved->parameters[$parameter->getName()])) {
                $parameters[$parameter->getName()] = $resolved->parameters[$parameter->getName()];

            } elseif ($type === null || $type->allowsNull()) {
                $parameters[$parameter->getName()] = null;

            } elseif (!$type->isBuiltin()) {

                if ($this->container->has($type->getName())) {
                    $parameters[$parameter->getName()] = $this->container->get($type->getName());
                } elseif ($type->getName() === ServerRequestInterface::class) {
                    // The parameter name is remembered so that it can be substituted by the ClosureHandler.
                    $requestParameters[] = $parameter->getName();
                }

            } elseif ($parameter->isDefaultValueAvailable()) {
                $parameters[$parameter->getName()] = $parameter->getDefaultValue();
            }
        }

        return [$parameters, $requestParameters];
    }

    /**
     * Algorithm for obtaining a Closure from a route and its handler.
     *
     * It is expected that the handler may be one of the following:
     *  * A class that has an "__invoke()" method;
     *  * An array representing classic callable syntax ([Controller::class, 'method']);
     *  * An Closure instance.
     *
     * @param MatchedRoute $resolved
     * @return RequestHandlerInterface
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function resolve(MatchedRoute $resolved): RequestHandlerInterface
    {
        $handler = $resolved->route->getAction();

        if (is_string($handler) && class_exists($handler)) {
            $handler = $this->container->get($handler);

            if (is_callable($handler)) {
                $handler = $handler(...); // __invoke(...)
            }
        }

        if (is_array($handler) && count($handler) === 2) {
            $class = array_shift($handler);
            $method = array_shift($handler);

            if (is_string($class) && class_exists($class)) {
                $controller = $this->container->get($class);

                $handler = $controller->{$method}(...);
            }
        }

        /** @var mixed|Closure $handler */
        if ($handler instanceof Closure) {
            [$parameters, $httpRequestParameters] = $this->resolveParameters($handler, $resolved);
            return $this->handlerFactory->create($handler, $httpRequestParameters, $parameters);
        }

        throw new InvalidArgumentException('Invalid handler provided');
    }
}
