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

namespace Silence\Event\Types;

/**
 * An event that signals the launch of the application.
 *
 * Called before the HTTP request processing chain is launched.
 */
interface BeforeKernelRunInterface
{
}
