<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

namespace Silence\Routing;

use Exception;

/**
 * An exception that should be thrown if the route could not be resolved.
 */
class RouteNotFound extends Exception
{
    /** @var string $message */
    protected $message = 'Route not found.';
}
