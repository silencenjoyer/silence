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

namespace Silence\ErrorHandler\Registerer;

use Silence\ErrorHandler\ErrorHandler;

/**
 * This is a wrapper for PHP handler registries.
 */
final readonly class HandlersRegisterer
{
    public function __construct(
        private ErrorHandler $errorHandler,
    ) {
    }

    /**
     * This method registers an exception handler function in the application.
     *
     * @return self
     */
    public function exceptionHandler(): self
    {
        set_exception_handler($this->errorHandler->exceptionHandler(...));

        return $this;
    }

    /**
     * This method registers an error handler function in the application.
     *
     * @return self
     */
    public function errorHandler(): self
    {
        set_error_handler($this->errorHandler->errorHandler(...));

        return $this;
    }

    /**
     * This method registers a shutdown function in the application.
     *
     * @return $this
     */
    public function shutdownFunction(): self
    {
        register_shutdown_function($this->errorHandler->shutdownFunction(...));

        return $this;
    }
}
