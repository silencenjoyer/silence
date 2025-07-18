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

namespace Silence\ErrorHandler\RendererResolvers;

use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;

interface RendererResolverInterface
{
    public function resolve(ServerRequestInterface $request): ?ThrowableRendererInterface;
}
