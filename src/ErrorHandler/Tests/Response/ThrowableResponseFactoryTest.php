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

namespace Silence\ErrorHandler\Tests\Response;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Silence\ErrorHandler\ErrorHandler;
use Silence\ErrorHandler\RendererResolvers\RendererResolverInterface;
use Silence\ErrorHandler\Renderers\JsonRenderer;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Silence\ErrorHandler\Response\ThrowableResponseFactory;
use Silence\HttpSpec\HttpHeaders\BodyMeta;

class ThrowableResponseFactoryTest extends TestCase
{
    private ThrowableResponseFactory $throwableResponseFactory;
    private ResponseFactoryInterface $responseFactory;
    private RendererResolverInterface $rendererResolver;
    private ErrorHandler $errorHandler;
    private ServerRequestInterface $request;
    private ResponseInterface $response;
    private ThrowableRendererInterface $throwableRenderer;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $this->rendererResolver = $this->createMock(RendererResolverInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->throwableRenderer = $this->createMock(ThrowableRendererInterface::class);

        $this->errorHandler = new ErrorHandler($this->throwableRenderer);
        $this->errorHandler->setDebugMode(true);

        $this->throwableResponseFactory = new ThrowableResponseFactory(
            $this->responseFactory,
            $this->rendererResolver,
            $this->errorHandler,
        );
    }

    /**
     * @throws Exception
     */
    public function testOrchestration(): void
    {
        $this->rendererResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->request)
            ->willReturn(new JsonRenderer())
        ;

        $this->responseFactory
            ->expects($this->once())
            ->method('createResponse')
            ->with(500)
            ->willReturn($this->response)
        ;

        $this->response
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with(BodyMeta::CONTENT_TYPE, JsonRenderer::CONTENT_TYPE)
            ->willReturnSelf()
        ;

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('write')
            ->with($this->isJson())
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream)
        ;

        $this->assertSame(
            $this->throwableResponseFactory->create($this->request, new \Exception()),
            $this->response
        );
    }
}
