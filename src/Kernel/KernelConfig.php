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

use Silence\KernelExtension\ExtensionInterface;

/**
 * A class responsible for configuring the application kernel.
 *
 * Settings can be specified from the client code.
 *
 * Supports values such as:
 *  - a list of extensions that will be connected and registered
 *  - basePath the root directory of the application
 *  - names of .env files that will be connected
 */
final class KernelConfig
{
    /**
     * The root directory of the application.
     *
     * @var string
     */
    protected string $basePath;
    /**
     * List of extensions that will be connected and registered.
     *
     * @var list<ExtensionInterface>
     */
    protected array $extensions = [];
    /**
     * Names of .env files that will be used by default.
     *
     * @var list<string>
     */
    protected array $dotEnvFiles = [
        '.env',
        '.env.dev',
        '.env.local',
    ];
    /**
     * @var list<string> 
     */
    protected array $configFiles = ['config'];

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Factory method for creating an KernelConfig object with the specified {@see $basePath}.
     *
     * @param string $basePath
     * @return self
     */
    public static function withBasePath(string $basePath): self
    {
        return new self($basePath);
    }

    /**
     * Registration of application extension.
     *
     * @param ExtensionInterface $ext
     * @return $this
     */
    public function withExtension(ExtensionInterface $ext): KernelConfig
    {
        $this->extensions[] = $ext;
        return $this;
    }

    /**
     * Registration of the list of application extensions.
     *
     * @param list<ExtensionInterface> $extensions
     * @return KernelConfig
     */
    public function withExtensions(array $extensions): KernelConfig
    {
        foreach ($extensions as $ext) {
            $this->withExtension($ext);
        }
        return $this;
    }

    /**
     * Getter for the list of extensions that will be registered in the application.
     *
     * @return list<ExtensionInterface>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Registration of configuration files.
     *
     * @param string $file
     * @return $this
     */
    public function withConfigFile(string $file): KernelConfig
    {
        $this->configFiles[] = $file;
        return $this;
    }

    /**
     * Registration of the list of configuration files.
     *
     * @param array<string> $configFiles
     * @return $this
     */
    public function withConfigFiles(array $configFiles): KernelConfig
    {
        foreach ($configFiles as $file) {
            $this->withConfigFile($file);
        }
        return $this;
    }

    /**
     * Getter for list of the application configuration files.
     *
     * @return list<string>
     */
    public function getConfigFiles(): array
    {
        return $this->configFiles;
    }

    /**
     * Getter for the root directory of the application.
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Specifies which .env files the application will work with.
     *
     * The passed value will overwrite the default list of files {@see $dotEnvFiles}.
     *
     * Relative paths should be passed from the application's root directory.
     * Absolute paths will be created automatically based on {@see $basePath}.
     *
     * @param list<string> $filenames
     * @return KernelConfig
     */
    public function withDotEnvs(array $filenames): KernelConfig
    {
        $this->dotEnvFiles = $filenames;
        return $this;
    }

    /**
     * Gets a list of all .env files that the application will work with.
     *
     * Returns absolute paths from the application's root directory.
     *
     * @return list<string>
     */
    public function getDotEnvs(): array
    {
        return array_map(fn(string $name): string => $this->basePath . '/' . $name, $this->dotEnvFiles);
    }
}
