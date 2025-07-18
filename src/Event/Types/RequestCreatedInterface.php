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
 * Event of a created HTTP request.
 *
 * Called before the chain of HTTP request handlers is launched.
 */
interface RequestCreatedInterface extends HasRequestInterface
{
}
