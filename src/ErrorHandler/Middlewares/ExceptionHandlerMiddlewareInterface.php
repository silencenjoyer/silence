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

namespace Silence\ErrorHandler\Middlewares;

use Psr\Http\Server\MiddlewareInterface;

/**
 * An interface that is identical to {@see MiddlewareInterface} in terms of methods and signatures.
 *
 * However, it represents a separate branch of logic designed to handle middleware chain exceptions.
 */
interface ExceptionHandlerMiddlewareInterface extends MiddlewareInterface
{
}
