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

use Exception;
use Silence\HttpSpec\HttpCodes\CodeEnum;

/**
 * A subtype of exceptions for HTTP errors.
 *
 * The code is intended to be used not as a program exit code, but as an HTTP Status Code.
 * For this reason, since this is a base class, the default code value is 500, an internal server error.
 * This is necessary if a more detailed exception is not thrown.
 *
 * {@see https://developer.mozilla.org/ru/docs/Web/HTTP/Reference/Status/500}
 */
class HttpException extends Exception
{
    /** @var int<100, 599> $code */
    protected $code = CodeEnum::INTERNAL_SERVER_ERROR->value;
}
