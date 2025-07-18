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

namespace Silence\ErrorHandler\RendererResolvers;

use Silence\HttpSpec\HttpHeaders\ContentNegotiation;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\Renderers\HtmlRenderer;
use Silence\ErrorHandler\Renderers\JsonRenderer;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Silence\HeaderParser\HeaderParser;
use Silence\HeaderParser\QualityNegotiator;

readonly class ContentTypeRendererResolver implements RendererResolverInterface
{
    protected const string ANY_CONTENT_TYPE = '*/*';

    /**
     * @param ContainerInterface $container
     * @param HeaderParser $headerParser
     * @param QualityNegotiator $qualityNegotiator
     * @param array<string, string> $contentRenderersMapper
     */
    public function __construct(
        private ContainerInterface $container,
        private HeaderParser $headerParser,
        private QualityNegotiator $qualityNegotiator,
        private array $contentRenderersMapper = [
            JsonRenderer::CONTENT_TYPE => JsonRenderer::class,
            HtmlRenderer::CONTENT_TYPE => HtmlRenderer::class,
            self::ANY_CONTENT_TYPE => HtmlRenderer::class,
        ],
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolve(ServerRequestInterface $request): ?ThrowableRendererInterface
    {
        if (!$request->hasHeader(ContentNegotiation::ACCEPT)) {
            return null;
        }

        $accept = $request->getHeader(ContentNegotiation::ACCEPT);

        if ($accept === []) {
            return null;
        }

        $contentTypes = $this->qualityNegotiator->getSortedHeaderValues(
            $this->headerParser->getHeaderValuesWithParams($accept)
        );

        foreach ($contentTypes as $type) {
            if (isset($this->contentRenderersMapper[$type])) {

                $result = $this->container->get($this->contentRenderersMapper[$type]);
                assert($result instanceof ThrowableRendererInterface);
                return $result;
            }
        }

        return null;
    }
}
