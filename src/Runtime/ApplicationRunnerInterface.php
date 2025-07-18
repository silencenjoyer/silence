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
 * The interface is identical to {@see RunnerInterface} in terms of methods and signatures, but declares a separate
 * branch of logic responsible for executing the application algorithm.
 */
interface ApplicationRunnerInterface extends RunnerInterface
{
}
