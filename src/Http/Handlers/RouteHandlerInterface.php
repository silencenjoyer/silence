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

namespace Silence\Http\Handlers;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * An interface that is identical to {@see RequestHandlerInterface} in terms of methods and signatures.
 *
 * However, it represents a separate branch of logic designed to resolve application routes.
 */
interface RouteHandlerInterface extends RequestHandlerInterface
{
}
