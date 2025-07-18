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

namespace Silence\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface for the factory for creating and assembling PSR-7 request implementations.
 */
interface RequestFactoryInterface
{
    /**
     * Must return the {@see ServerRequestInterface} collected from the incoming request data.
     *
     * @return ServerRequestInterface
     */
    public function create(): ServerRequestInterface;
}
