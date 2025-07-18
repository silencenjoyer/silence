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

namespace Silence\KernelExtension;

use Silence\Kernel\KernelConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * An interface for application extension registrars.
 * Provides methods for declaring definitions for the container, as well as for executing some logic before the kernel starts.
 */
interface ExtensionInterface
{
    /**
     * Service register, definitions adding etc.
     *
     * @param ContainerBuilder $container
     * @param KernelConfig $config
     * @return void
     */
    public function configure(ContainerBuilder $container, KernelConfig $config): void;

    /**
     * Logic with compiled container.
     *
     * @param ContainerBuilder $container
     * @param KernelConfig $config
     * @return void
     */
    public function boot(ContainerBuilder $container, KernelConfig $config): void;
}
