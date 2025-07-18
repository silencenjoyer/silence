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

namespace Silence\ErrorHandler\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

interface ThrowableResponseFactoryInterface
{
    /**
     * Must create readable response for displaying a throwable.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $e
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, Throwable $e): ResponseInterface;
}
