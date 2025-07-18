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

namespace Silence\Kernel;

/**
 * Kernel interface.
 *
 * The kernel is designed to be the central element of the system, responsible for its creation and initial configuration.
 */
interface KernelInterface
{
    /**
     * Must accept the kernel configuration when creating an object.
     *
     * @param KernelConfig $config
     */
    public function __construct(KernelConfig $config);

    /**
     * Must have a mechanism for launching the application and its algorithms.
     *
     * @return void
     */
    public function run(): void;
}
