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
 * Event marking the completion of kernel.
 *
 * Called after processing the chain of HTTP request handlers and sending the response to the client.
 */
interface KernelTerminatedInterface
{
}
