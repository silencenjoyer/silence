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

namespace Silence\Http\Exceptions;

/**
 * An HTTP error subtype indicating that the requested resource was not found.
 *
 * For a more detailed description of HTTP exceptions, see: {@see HttpException}.
 *
 * {@see https://developer.mozilla.org/ru/docs/Web/HTTP/Reference/Status/404}
 */
class NotFoundHttpException extends HttpException
{
    protected $code = 404;
    /** @var string $message */
    protected $message = 'Not Found';
}
