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

namespace Silence\Event;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * A backup event dispatcher, in case the real one is not registered.
 *
 * In fact, no event processing will take place in it.
 */
final class NullDispatcher implements EventDispatcherInterface
{
    /**
     * {@inheritDoc}
     *
     * @param object $event
     * @return object
     */
    public function dispatch(object $event): object
    {
        return $event;
    }
}
