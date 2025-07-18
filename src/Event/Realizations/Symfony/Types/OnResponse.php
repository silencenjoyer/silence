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

namespace Silence\Event\Realizations\Symfony\Types;

use Psr\Http\Message\ResponseInterface;
use Silence\Event\Types\OnResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class OnResponse extends Event implements OnResponseInterface
{
    public function __construct(private readonly ResponseInterface $response)
    {
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
