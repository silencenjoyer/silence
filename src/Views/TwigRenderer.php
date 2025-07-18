<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare (strict_types=1);

namespace Silence\Views;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Silence\HttpSpec\HttpHeaders\BodyMeta;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Template renderer using the powerful Twig template engine.
 */
class TwigRenderer implements ViewRendererInterface
{
    protected const string CONTENT_TYPE = 'text/html';

    public function __construct(
        protected Environment $twig,
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return ResponseInterface
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $view, array $params = []): ResponseInterface
    {
        $content = $this->twig->render($view, $params);

        return $this->responseFactory->createResponse()
            ->withHeader(BodyMeta::CONTENT_TYPE, self::CONTENT_TYPE)
            ->withBody($this->streamFactory->createStream($content))
        ;
    }
}
