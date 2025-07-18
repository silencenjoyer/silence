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

namespace Silence\ErrorHandler\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Silence\ErrorHandler\Response\ThrowableResponseFactoryInterface;
use Throwable;

/**
 * Backup middleware for processing exceptions, according to PSR-15, section 1.4
 */
class ThrowableMiddleware implements ExceptionHandlerMiddlewareInterface
{
    public function __construct(
        protected ThrowableResponseFactoryInterface $throwableResponseFactory
    ) {
    }

    /**
     * An exception reserve processing.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->throwableResponseFactory->create($request, $exception);
        }
    }
}
