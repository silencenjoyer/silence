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

namespace Silence\ErrorHandler\Tests\Renderers;

use Exception;
use PHPUnit\Framework\TestCase;
use Silence\ErrorHandler\Renderers\JsonRenderer;
use Silence\HttpSpec\HttpHeaders\BodyMeta;

class JsonRendererTest extends TestCase
{
    private JsonRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new JsonRenderer();
    }

    public function testCommonIsJson(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $this->assertJson($dto->readableContent);
    }

    public function testDetailedIsJson(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderDetailed($throwable);

        $this->assertJson($dto->readableContent);
    }

    public function testCommonHasShortDetails(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $data = json_decode($dto->readableContent, true);

        $this->assertArrayHasKey('message', $data);
    }

    public function testCommonHasNoDetails(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $data = json_decode($dto->readableContent, true);

        $this->assertArrayNotHasKey('type', $data);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertArrayNotHasKey('file', $data);
        $this->assertArrayNotHasKey('line', $data);
        $this->assertArrayNotHasKey('trace', $data);
    }

    public function testCommonHasContentTypeHeader(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $this->assertArrayHasKey(BodyMeta::CONTENT_TYPE, $dto->headers);

        $this->assertSame($dto->headers[BodyMeta::CONTENT_TYPE], JsonRenderer::CONTENT_TYPE);
    }

    public function testDetailedHasContentTypeHeader(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderDetailed($throwable);

        $this->assertArrayHasKey(BodyMeta::CONTENT_TYPE, $dto->headers);

        $this->assertSame($dto->headers[BodyMeta::CONTENT_TYPE], JsonRenderer::CONTENT_TYPE);
    }

    public function testDetailedHasDetails(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderDetailed($throwable);

        $data = json_decode($dto->readableContent, true);

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('file', $data);
        $this->assertArrayHasKey('line', $data);
        $this->assertArrayHasKey('trace', $data);
    }
}
