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

namespace Silence\ErrorHandler\Renderers;

use Silence\HttpSpec\HttpHeaders\BodyMeta;
use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\ErrorDto;
use Throwable;

class JsonRenderer implements ThrowableRendererInterface
{
    /** Content type for Content-Type HTTP header. */
    public const string CONTENT_TYPE = 'application/json';

    protected int $format = JSON_UNESCAPED_SLASHES
        | JSON_THROW_ON_ERROR
        | JSON_PRETTY_PRINT
        | JSON_INVALID_UTF8_SUBSTITUTE
        | JSON_UNESCAPED_UNICODE;

    /**
     * @param array<string, mixed> $data
     * @param Throwable $t
     * @return ErrorDto
     */
    private function encode(array $data, Throwable $t): ErrorDto
    {
        return new ErrorDto(
            json_encode($data, $this->format) ?: '',
            $t,
            [BodyMeta::CONTENT_TYPE => self::CONTENT_TYPE]
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param Throwable $t
     * @param ServerRequestInterface|null $request
     * @return ErrorDto
     */
    public function renderCommon(Throwable $t, ?ServerRequestInterface $request = null): ErrorDto
    {
        return $this->encode(
            [
                'message' => $t->getMessage(),
            ],
            $t
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param Throwable $t
     * @param ServerRequestInterface|null $request
     * @return ErrorDto
     */
    public function renderDetailed(Throwable $t, ?ServerRequestInterface $request = null): ErrorDto
    {
        return $this->encode(
            [
                'type' => $t::class,
                'code' => $t->getCode(),
                'message' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
                'trace' => $t->getTrace(),
            ],
            $t
        );
    }
}
