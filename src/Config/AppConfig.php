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
 * Application configuration object.
 * It is a repository from which you can get or insert values.
 *
 * Example of value extraction:
 * ```
 * $companyName = $appConfig->get('company.name');
 * ```
 *
 * Example of inserting a value:
 * ```
 * $appConfig->set('company.name', $localDepartmentName);
 * ```
 */
final class AppConfig implements ConfigInterface
{
    /**
     * @param array<array-key, mixed> $config
     */
    public function __construct(
        private array $config = []
    ) {
    }

    /**
     * Divides the path into parts.
     * Dot "." means nested element.
     * A backslash followed by a dot "\." indicates the presence of a dot in the name, without nested elements.
     *
     * Config example:
     * ```
     * [
     *      'company' => [
     *          'name' => 'Silence',
     *      ],
     * ]
     * ```
     * Extraction of the path:
     * ```
     * $this->splitPath('company.name')
     * ```
     *
     * Config example:
     * ```
     *  [
     *       'company.name' => 'Silence',
     *  ]
     * ```
     * Extraction of the path:
     * ```
     * $this->splitPath('company\.name')
     *```
     * @param string $path
     * @return list<string>
     */
    protected function splitPath(string $path): array
    {
        $parts = preg_split('/(?<!\\\\)\./', $path);
        return array_map(fn(string $part): string => str_replace('\.', '.', $part), $parts ?: []);
    }

    /**
     * Set the value in the config file at the specified path.<br>
     * See the rules for dividing the path: {@see splitPath()}.
     *
     * {@inheritDoc}
     *
     * @param string $path
     * @param mixed $value
     * @return ConfigInterface
     */
    public function set(string $path, mixed $value): ConfigInterface
    {
        $parts = $this->splitPath($path);

        $result = [];
        $temp = &$result;

        foreach ($parts as $item) {
            $temp[$item] = [];
            $temp = &$temp[$item];
        }

        $temp = $value;

        $this->config = array_merge($this->config, $result);

        return $this;
    }

    /**
     * Gets the value from the config file at the specified path.<br>
     * See the rules for dividing the path: {@see splitPath()}.
     *
     * {@inheritDoc}
     *
     * @param string $path
     * @param mixed $default
     */
    public function get(string $path, mixed $default = null): mixed
    {
        $param = $this->config;

        foreach ($this->splitPath($path) as $key) {
            if (!is_array($param) || !array_key_exists($key, $param)) {
                return $default;
            }
            $param = $param[$key];
        }

        return $param;
    }
}
