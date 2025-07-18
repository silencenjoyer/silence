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

namespace Silence\ErrorHandler\Renderers;

use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\ErrorDto;
use Throwable;

/**
 * Interface for exception renderers.
 */
interface ThrowableRendererInterface
{
    /**
     * Must render common information about the throwable.
     *
     * Suitable for production environment.
     *
     * @param Throwable $t
     * @param ServerRequestInterface|null $request
     * @return ErrorDto
     */
    public function renderCommon(Throwable $t, ?ServerRequestInterface $request = null): ErrorDto;

    /**
     * Must render detailed information about the throwable.
     *
     * Suitable for development environment.
     *
     * @param Throwable $t
     * @param ServerRequestInterface|null $request
     * @return ErrorDto
     */
    public function renderDetailed(Throwable $t, ?ServerRequestInterface $request = null): ErrorDto;
}
