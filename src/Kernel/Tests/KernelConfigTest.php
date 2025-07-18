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

namespace Silence\Kernel\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Silence\Kernel\KernelConfig;
use Silence\KernelExtension\ExtensionInterface;

class KernelConfigTest extends TestCase
{
    public function testWithBasePath(): void
    {
        $config = KernelConfig::withBasePath('/app');

        $this->assertSame('/app', $config->getBasePath());
    }

    public function testConstructorBasePathReturnsCorrectValue(): void
    {
        $config = new KernelConfig('/app');
        $this->assertSame('/app', $config->getBasePath());
    }

    /**
     * @throws Exception
     */
    public function testWithExtensionAddsSingleExtension(): void
    {
        $extension = $this->createMock(ExtensionInterface::class);

        $config = new KernelConfig('/app');
        $config->withExtension($extension);

        $this->assertSame([$extension], $config->getExtensions());
    }

    /**
     * @throws Exception
     */
    public function testWithExtensionsAddsMultipleExtensions(): void
    {
        $ext1 = $this->createMock(ExtensionInterface::class);
        $ext2 = $this->createMock(ExtensionInterface::class);

        $config = new KernelConfig('/app');
        $config->withExtensions([$ext1, $ext2]);

        $this->assertSame([$ext1, $ext2], $config->getExtensions());
    }

    public function testGetDotEnvsDefault(): void
    {
        $basePath = '/app';
        $config = new KernelConfig($basePath);

        $expected = [
            "$basePath/.env",
            "$basePath/.env.dev",
            "$basePath/.env.local",
        ];

        $this->assertSame($expected, $config->getDotEnvs());
    }

    public function testWithDotEnvsOverridesDefaults(): void
    {
        $basePath = '/app';
        $config = new KernelConfig($basePath);
        $config->withDotEnvs(['.env.testing', 'config/.env.db']);

        $expected = [
            "$basePath/.env.testing",
            "$basePath/config/.env.db",
        ];

        $this->assertSame($expected, $config->getDotEnvs());
    }

    /**
     * @throws Exception
     */
    public function testChainingMethods(): void
    {
        $ext1 = $this->createMock(ExtensionInterface::class);
        $ext2 = $this->createMock(ExtensionInterface::class);

        $config = KernelConfig::withBasePath('/root')
            ->withExtension($ext1)
            ->withExtensions([$ext2])
            ->withDotEnvs(['custom.env'])
        ;

        $this->assertSame(['/root/custom.env'], $config->getDotEnvs());
        $this->assertSame([$ext1, $ext2], $config->getExtensions());
    }
}
