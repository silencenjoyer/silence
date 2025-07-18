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
use Silence\ErrorHandler\Renderers\HtmlRenderer;
use Silence\HttpSpec\HttpHeaders\BodyMeta;

class HtmlRendererTest extends TestCase
{
    private HtmlRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new HtmlRenderer();
    }

    public function testCommonHasShortDetails(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $this->assertStringContainsStringIgnoringCase('Code', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('Message', $dto->readableContent);
    }

    public function testCommonHasNoDetails(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $this->assertStringNotContainsStringIgnoringCase('File', $dto->readableContent);
        $this->assertStringNotContainsStringIgnoringCase('Line', $dto->readableContent);
        $this->assertStringNotContainsStringIgnoringCase('Trace', $dto->readableContent);
        $this->assertStringNotContainsStringIgnoringCase('Previous Trace', $dto->readableContent);
    }

    public function testCommonHasContentTypeHeader(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderCommon($throwable);

        $this->assertArrayHasKey(BodyMeta::CONTENT_TYPE, $dto->headers);

        $this->assertSame($dto->headers[BodyMeta::CONTENT_TYPE], HtmlRenderer::CONTENT_TYPE);
    }

    public function testDetailedHasDetails(): void
    {
        $throwable = new Exception(previous: new Exception());

        $dto = $this->renderer->renderDetailed($throwable);

        $this->assertStringContainsStringIgnoringCase('Code', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('Message', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('File', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('Line', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('Trace', $dto->readableContent);
        $this->assertStringContainsStringIgnoringCase('Previous Trace', $dto->readableContent);
    }

    public function testDetailedHasContentTypeHeader(): void
    {
        $throwable = new Exception();

        $dto = $this->renderer->renderDetailed($throwable);

        $this->assertArrayHasKey(BodyMeta::CONTENT_TYPE, $dto->headers);

        $this->assertSame($dto->headers[BodyMeta::CONTENT_TYPE], HtmlRenderer::CONTENT_TYPE);
    }

}
