<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

namespace Silence\Http\Handlers;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * MiddlewareRunnerInterface is no different from RequestHandlerInterface in terms of methods or signatures,
 * but it declares a separate logical branch of handlers responsible for running middlewares.
 */
interface MiddlewareRunnerInterface extends RequestHandlerInterface
{
}
