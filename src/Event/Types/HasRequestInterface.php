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

use Psr\Http\Message\ServerRequestInterface;

/**
 * The interface of the event that is associated with the incoming request and will contain it.
 *
 * Declares the request getter so that handlers can retrieve and process it.
 */
interface HasRequestInterface
{
    /**
     * Getter method for the HTTP request object associated with the event.
     *
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;
}
