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

namespace Silence\Event\Realizations\Symfony\Types;

use Psr\Http\Message\ServerRequestInterface;
use Silence\Event\Types\RouteResolvedInterface;
use Silence\Routing\Matcher\MatchedRoute;
use Symfony\Contracts\EventDispatcher\Event;

final class RouteResolved extends Event implements RouteResolvedInterface
{
    public function __construct(
        private readonly MatchedRoute $route,
        private readonly ServerRequestInterface $request
    ) {
    }

    public function getRoute(): MatchedRoute
    {
        return $this->route;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
