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

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Silence\ErrorHandler\ErrorDto;
use Silence\ErrorHandler\ErrorHandler;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;

class ErrorHandlerTest extends TestCase
{
    private ThrowableRendererInterface $renderer;
    private LoggerInterface $logger;
    private ErrorHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->renderer = $this->createMock(ThrowableRendererInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new ErrorHandler($this->renderer, $this->logger);
    }

    public function testSetDebugMode(): void
    {
        $this->handler->setDebugMode(true);

        $exception = new RuntimeException('Test');
        $errorDto = new ErrorDto('Test Content', $exception);

        $this->renderer->expects($this->once())
            ->method('renderDetailed')
            ->with($exception)
            ->willReturn($errorDto)
        ;

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Test')
        ;

        $result = $this->handler->handle($exception);
        $this->assertSame($errorDto, $result);
    }

    public function testHandleWithoutDebug(): void
    {
        $this->handler->setDebugMode(false);

        $exception = new RuntimeException('Oops');
        $dto = new ErrorDto('Test Content', $exception);

        $this->renderer->expects($this->once())
            ->method('renderCommon')
            ->with($exception)
            ->willReturn($dto);

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Oops');

        $result = $this->handler->handle($exception);
        $this->assertSame($dto, $result);
    }

    public function testExceptionHandlerSkipsIfNotRegistered(): void
    {
        ob_start();
        $this->handler->exceptionHandler(new \RuntimeException('Should not display'));
        $out = ob_get_clean();
        $this->assertEmpty($out);
    }

    public function testErrorHandlerSkipsIfNotRegistered(): void
    {
        $result = $this->handler->errorHandler(E_USER_WARNING, 'msg', 'file.php', 123);
        $this->assertFalse($result);
    }

    public function testHandleWithCustomRenderer(): void
    {
        $this->handler->setDebugMode(true);

        $t = new \RuntimeException('Custom');
        $dto = new ErrorDto('Test Content', $t);

        $this->renderer->expects($this->once())
            ->method('renderDetailed')
            ->with($t)
            ->willReturn($dto)
        ;

        $result = $this->handler->handle($t, null, $this->renderer);
        $this->assertSame($dto, $result);
    }
}
