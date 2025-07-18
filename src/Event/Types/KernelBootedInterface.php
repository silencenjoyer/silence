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
 * The event of the booted kernel.
 *
 * Called after the container has been assembled and extensions have been connected.
 */
interface KernelBootedInterface
{
}
