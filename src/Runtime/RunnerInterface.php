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

namespace Silence\Runtime;

/**
 * An interface for classes that have a runnable algorithm.
 */
interface RunnerInterface
{
    /**
     * Execution of the algorithm.
     *
     * @return void
     */
    public function run(): void;
}
