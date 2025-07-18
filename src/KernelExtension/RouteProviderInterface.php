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

namespace Silence\KernelExtension;

use Silence\Routing\RouteGroupInterface;
use Silence\Routing\RouteInterface;

/**
 * Interface for application route provider objects.
 *
 * Must be able to provide a list of routes and/or route groups that will be available in the application.
 */
interface RouteProviderInterface
{
    /**
     * Must provide a list of routes and/or route groups that will be available in the application.
     *
     * @return list<RouteInterface|RouteGroupInterface>
     */
    public function getRoutes(): array;
}
