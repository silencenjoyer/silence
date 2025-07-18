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

namespace Silence\Config;

/**
 * Factory for creating application context.
 */
final class AppContextFactory
{
    /**
     * A factory method that takes the application environment and debug mode values to create context object.
     *
     * Could be useful for centralizing object creation, separating code from creation, and for dependency container.
     *
     * @param string $env
     * @param bool $debug
     * @return AppContext
     */
    public static function create(string $env, bool $debug): AppContext
    {
        return new AppContext($env, $debug);
    }
}
