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

use Silence\HttpSpec\HttpCodes\CodeEnum;
use Throwable;
use Silence\Http\Exceptions\HttpException;
use Stringable;

final readonly class ErrorDto implements Stringable
{
    /**
     * @param string $readableContent
     * @param Throwable $throwable
     * @param array<string, string> $headers
     */
    public function __construct(
        public string $readableContent,
        public Throwable $throwable,
        public array $headers = []
    ) {
    }

    /**
     * Displays readable information about the throwable.
     *
     * {@inheritDoc}
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->readableContent;
    }

    /**
     * Provides throwable status code.
     *
     * @return CodeEnum
     */
    public function getStatusCode(): CodeEnum
    {
        if ($this->throwable instanceof HttpException) {
            return CodeEnum::from($this->throwable->getCode());
        }

        return CodeEnum::INTERNAL_SERVER_ERROR;
    }
}
