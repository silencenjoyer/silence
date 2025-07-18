<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare (strict_types = 1);

namespace Silence\Http\Emitters;

use Psr\Http\Message\ResponseInterface;

/**
 * Describes the interface of an object that must send response headers and content to the sender of an HTTP request.
 */
interface EmitterInterface
{
    /**
     * This is where headers and content should be sent. Additional checks may be performed.
     *
     * @param ResponseInterface $response
     * @return void
     */
    public function emit(ResponseInterface $response): void;
}
