<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare (strict_types=1);

namespace Silence\KernelExtension;

use Silence\Kernel\KernelConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * It is an abstract layer that makes it possible not to describe the method that is not needed for the extension.
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param ContainerBuilder $container
     * @param KernelConfig $config
     * @return void
     */
    public function configure(ContainerBuilder $container, KernelConfig $config): void
    {
    }

    /**
     * {@inheritDoc}
     *
     * @param ContainerBuilder $container
     * @param KernelConfig $config
     * @return void
     */
    public function boot(ContainerBuilder $container, KernelConfig $config): void
    {
    }
}
