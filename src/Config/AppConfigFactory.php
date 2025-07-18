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
 * Factory for creating configurations.
 * See the configuration description in the corresponding class: {@see AppConfig}.
 */
final class AppConfigFactory
{
    /**
     * Factory method for creating a configuration.
     * Accepts the path to the configuration files, as well as the file names.
     *
     * It also attempts to connect files that match the file name and application environment in {name}_{env} format.
     * Env configuration files override values under the same keys in the regular file.
     *
     * Could be useful for centralizing object creation, separating code from creation, and for dependency container.
     *
     * @param string $env The application environment.
     * @param string $path The path to the configuration files.
     * @param list<string> $files File names that contain the configuration (without file extensions).
     * @return AppConfig
     */
    public static function create(string $env, string $path, array $files): AppConfig
    {
        $files = array_merge($files, array_map(fn(string $name): string => $name . '_' . $env, $files));
        $configPaths = array_map(fn(string $name): string => sprintf('%s/%s.php', $path, $name), $files);

        $config = [];
        foreach (array_filter($configPaths, fn(string $path): bool => file_exists($path)) as $configFile) {
            $config = array_merge($config, (array) require $configFile);
        }

        return new AppConfig($config);
    }
}
