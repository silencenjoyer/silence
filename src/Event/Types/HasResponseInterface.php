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

use Psr\Http\Message\ResponseInterface;

/**
 * The interface of an event associated with a returnable response and containing it.
 *
 * Declares the response getter so that handlers can retrieve and process it.
 */
interface HasResponseInterface
{
    /**
     * Getter method for the HTTP response object associated with the event.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;
}
