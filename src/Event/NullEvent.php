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
use RuntimeException;
use Silence\Event\Types\BeforeKernelRunInterface;
use Silence\Event\Types\KernelBootedInterface;
use Silence\Event\Types\KernelTerminatedInterface;
use Silence\Event\Types\OnResponseInterface;
use Silence\Event\Types\RequestCreatedInterface;
use Silence\Event\Types\RouteNotFoundInterface;
use Silence\Event\Types\RouteResolvedInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * The Event reserve class is used if no event handler is registered.
 *
 * It does not imply any logic.
 */
final class NullEvent implements
    KernelBootedInterface,
    BeforeKernelRunInterface,
    KernelTerminatedInterface,
    RequestCreatedInterface,
    RouteResolvedInterface,
    RouteNotFoundInterface,
    OnResponseInterface
{
    private function reserveClassCalled(): never
    {
        throw new RuntimeException('Null event is a reserve class. Did you forget to register the event factory?');
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        $this->reserveClassCalled();
    }

    /**
     * @return MatchedRoute
     */
    public function getRoute(): MatchedRoute
    {
        $this->reserveClassCalled();
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        $this->reserveClassCalled();
    }
}
