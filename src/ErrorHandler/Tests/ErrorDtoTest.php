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

namespace Silence\ErrorHandler\Tests;

use PHPUnit\Framework\TestCase;
use Silence\ErrorHandler\ErrorDto;
use Silence\Http\Exceptions\NotFoundHttpException;
use Silence\HttpSpec\HttpCodes\CodeEnum;

class ErrorDtoTest extends TestCase
{
    public function testStatusCode500(): void
    {
        $dto = new ErrorDto('Test Data', new \Error());

        $this->assertSame(CodeEnum::INTERNAL_SERVER_ERROR, $dto->getStatusCode());
    }

    public function testStatusCodeHttpError(): void
    {
        $dto = new ErrorDto('Test Data', new NotFoundHttpException());

        $this->assertSame(CodeEnum::NOT_FOUND, $dto->getStatusCode());
    }
}
