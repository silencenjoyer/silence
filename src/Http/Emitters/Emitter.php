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

namespace Silence\Http\Emitters;

use Psr\Http\Message\ResponseInterface;

class Emitter implements EmitterInterface
{
    /**
     * {@inheritDoc}
     *
     * The response is being sent.
     *
     * A check is performed to ensure that the headers have not already been sent before attempting to send them.
     *
     * @param ResponseInterface $response
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        if (!headers_sent()) {
            http_response_code($response->getStatusCode());
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }

        echo $response->getBody();
    }
}
