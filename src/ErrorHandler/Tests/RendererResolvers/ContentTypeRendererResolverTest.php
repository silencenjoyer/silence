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

namespace Silence\ErrorHandler\Tests\RendererResolvers;

use HttpHeaderException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\RendererResolvers\ContentTypeRendererResolver;
use Silence\ErrorHandler\Renderers\HtmlRenderer;
use Silence\ErrorHandler\Renderers\JsonRenderer;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Silence\HeaderParser\HeaderParser;
use Silence\HeaderParser\QualityNegotiator;

class ContentTypeRendererResolverTest extends TestCase
{
    private ContentTypeRendererResolver $resolver;
    private ContainerInterface $container;
    private HeaderParser $headerParser;
    private QualityNegotiator $qualityNegotiator;
    private ServerRequestInterface $request;
    private ThrowableRendererInterface $throwableRenderer;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createMock(ContainerInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->throwableRenderer = $this->createMock(ThrowableRendererInterface::class);

        $this->headerParser = new HeaderParser();
        $this->qualityNegotiator = new QualityNegotiator();

        $this->resolver = new ContentTypeRendererResolver(
            $this->container,
            $this->headerParser,
            $this->qualityNegotiator,
            [
                HtmlRenderer::CONTENT_TYPE => HtmlRenderer::class,
                JsonRenderer::CONTENT_TYPE => JsonRenderer::class,
            ]
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws HttpHeaderException
     */
    public function testReturnsNullOnAbsentHeader(): void
    {
        $this->assertNull($this->resolver->resolve($this->request));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws HttpHeaderException
     */
    public function testReturnsNullOnEmptyHeader(): void
    {
        $this->request->expects($this->once())->method('hasHeader')->willReturn(true);
        $this->request->expects($this->once())->method('getHeader')->willReturn([]);

        $this->assertNull($this->resolver->resolve($this->request));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws HttpHeaderException
     * @throws NotFoundExceptionInterface
     */
    public function testReturnsWhenExists(): void
    {
        $this->request->expects($this->once())->method('hasHeader')->willReturn(true);
        $this->request->expects($this->once())->method('getHeader')->willReturn([HtmlRenderer::CONTENT_TYPE]);

        $this->container->expects($this->once())
            ->method('get')
            ->with(HtmlRenderer::class)
            ->willReturn($this->throwableRenderer)
        ;

        $this->assertSame(
            $this->resolver->resolve($this->request),
            $this->throwableRenderer
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws HttpHeaderException
     */
    public function testReturnTheHighestPriority(): void
    {
        $this->request->expects($this->once())->method('hasHeader')->willReturn(true);
        $this->request->expects($this->once())->method('getHeader')->willReturn([
            JsonRenderer::CONTENT_TYPE . ';q=1.0, application/xml;q=0.9'
        ]);

        $this->container->expects($this->once())
            ->method('get')
            ->with(JsonRenderer::class)
            ->willReturn($this->throwableRenderer)
        ;

        $this->assertSame(
            $this->resolver->resolve($this->request),
            $this->throwableRenderer
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws HttpHeaderException
     */
    public function testReturnNullWhenNotExists(): void
    {
        $this->request->expects($this->once())->method('hasHeader')->willReturn(true);
        $this->request->expects($this->once())->method('getHeader')->willReturn(['application/octet-stream']);

        $this->container->expects($this->never())->method('get');

        $this->assertNull($this->resolver->resolve($this->request));
    }
}
