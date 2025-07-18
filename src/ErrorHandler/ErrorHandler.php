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

namespace Silence\ErrorHandler;

use Closure;
use ErrorException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Silence\ErrorHandler\Registerer\HandlersRegisterer;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Throwable;

final class ErrorHandler
{
    /** @var int 32 KB */
    protected const int DEFAULT_MEMORY_RESERVE = 32768;

    private bool $debug;
    private bool $registered = false;
    private readonly ThrowableRendererInterface $throwableRenderer;
    private readonly LoggerInterface $logger;
    /**
     * The property is only written. This is expected behavior since it reserves memory.
     *
     * @noinspection PhpPropertyOnlyWrittenInspection
     * @phpstan-ignore property.onlyWritten
     */
    private string $memoryReserve = '';
    private ?int $memoryReserveSize;

    public function __construct(
        ThrowableRendererInterface $throwableRenderer,
        LoggerInterface $logger = new NullLogger(),
        ?int $memoryReserveSize = self::DEFAULT_MEMORY_RESERVE
    ) {
        $this->throwableRenderer = $throwableRenderer;
        $this->logger = $logger;
        $this->memoryReserveSize = $memoryReserveSize;
    }

    /**
     * The method displays throwable.
     *
     * @param ErrorDto $errorDto
     * @return void
     */
    private function displayResponse(ErrorDto $errorDto): void
    {
        http_response_code($errorDto->getStatusCode()->value);

        echo $errorDto;
    }

    /**
     * Shutdown functions may also call register_shutdown_function() themselves to add a shutdown function to the end
     * of the queue.
     *
     * {@see https://www.php.net/manual/en/function.register-shutdown-function.php}
     *
     * @param Closure $shutdown
     * @return void
     */
    private function registerShutdownFunctionToTheEnd(Closure $shutdown): void
    {
        $shutdown = static function () use ($shutdown): void {
            register_shutdown_function($shutdown);
        };

        register_shutdown_function($shutdown);
    }

    /**
     * Creates a HandlersRegisterer for registering PHP handlers.
     *
     * This is a tough combination, but no other registrars are expected to appear anytime soon.
     *
     * @return HandlersRegisterer
     */
    private function registerer(): HandlersRegisterer
    {
        return new HandlersRegisterer($this);
    }

    /**
     * Resets memory reserve of current event handler.
     *
     * @return void
     */
    private function flushMemoryReserve(): void
    {
        $this->memoryReserve = '';
    }

    /**
     * Allocates memory reserve for current error handler.
     *
     * @return void
     */
    private function allocateMemoryReserve(): void
    {
        $this->memoryReserve = str_repeat('x', $this->memoryReserveSize ?? 0);
    }

    /**
     * Set debug mode for the current instance of the error handler.
     *
     * @param bool $debug
     * @return void
     */
    public function setDebugMode(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * Setter for {@see memoryReserveSize}.
     *
     * Also allocates memory if handler is registered.
     *
     * @param int|null $memoryReserveSize
     * @return void
     */
    public function setMemoryReserveSize(?int $memoryReserveSize): void
    {
        $this->memoryReserveSize = $memoryReserveSize;

        if ($this->registered) {
            $this->memoryReserveSize !== null
                ? $this->allocateMemoryReserve()
                : $this->flushMemoryReserve()
            ;
        }
    }

    /**
     * An exception handler method.
     *
     * @param Throwable $t
     * @return void
     */
    public function exceptionHandler(Throwable $t): void
    {
        if (!$this->registered) {
            return;
        }

        $this->displayResponse($this->handle($t));

        $this->registerShutdownFunctionToTheEnd(static function (): void {
            exit(1);
        });
    }

    /**
     * An error handler method.
     *
     * In fact, error handling is delegated to the exception handler.
     *
     * @throws ErrorException
     */
    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        if (!$this->registered) {
            return false;
        }

        // Bit masc. If code is not activated in error_reporting.
        if (!(error_reporting() & $errno)) {
            return true;
        }

        throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }

    /**
     * Method for handling fatal errors.
     *
     * @return void
     */
    public function shutdownFunction(): void
    {
        if (!$this->registered) {
            return;
        }

        $this->flushMemoryReserve();

        if (($e = error_get_last()) !== null) {

            $error = new ErrorException($e['message'], $e['type'], $e['type'], $e['file'], $e['line']);
            $this->displayResponse($this->handle($error));
        }
    }

    /**
     * The processing of the throwable object depends on the debug mode of the application.
     *
     * @param Throwable $t
     * @param ServerRequestInterface|null $request
     * @param ThrowableRendererInterface|null $renderer
     * @return ErrorDto
     */
    public function handle(
        Throwable $t,
        ?ServerRequestInterface $request = null,
        ?ThrowableRendererInterface $renderer = null
    ): ErrorDto {
        $renderer ??= $this->throwableRenderer;

        $this->logger->error($t->getMessage(), ['exception' => $t]);

        if ($this->debug) {
            return $renderer->renderDetailed($t, $request);
        }

        return $renderer->renderCommon($t, $request);
    }

    /**
     * Register PHP runtime handlers.
     *
     * Registration:
     * - Shutdown Function
     * - Exception Handler
     * - Error Handler
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->registered) {
            return;
        }

        if ($this->memoryReserveSize !== null) {
            $this->allocateMemoryReserve();
        }

        $this->registered = true;

        $this->registerer()
            ->shutdownFunction()
            ->exceptionHandler()
            ->errorHandler()
        ;
    }

    /**
     * Disable error handling for the current instance.
     *
     * @return void
     */
    public function disable(): void
    {
        $this->registered = false;

        $this->flushMemoryReserve();
    }
}
