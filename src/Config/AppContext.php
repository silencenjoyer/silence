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
 * An object containing basic information about the application context: locale, environment, etc.
 */
final class AppContext
{
    public const string DEFAULT_ENV = 'dev';
    public const string FALLBACK_LOCALE = 'en_US';

    public function __construct(
        private readonly string $env = self::DEFAULT_ENV,
        private readonly bool $debug = false,
        private string $locale = self::FALLBACK_LOCALE,
    ) {
    }

    /**
     * Provides the application environment.
     *
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * Indicator whether the application is running in debug mode.
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Application locale setter.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Application locale getter.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
