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

/**
 * A class that renders throwable in a readable format.
 */
readonly class HtmlRenderer implements ThrowableRendererInterface
{
    /** Content type for Content-Type HTTP header. */
    public const string CONTENT_TYPE = 'text/html';

    public function __construct(
        private string $viewPath = __DIR__ . '/../resources',
        private string $common = 'common',
        private string $detailed = 'detailed'
    ) {
    }

    /**
     * Internal method for rendering only the view file.
     *
     * @param string $view
     * @param array<string, mixed> $params
     * @return string
     */
    private function renderView(string $view, array $params = []): string
    {
        foreach ($params as $var => $value) {
            $$var = $value;
        }

        ob_start();
        include $this->viewPath . "/$view.php";
        return ob_get_clean() ?: '';
    }

    /**
     * Rendering the view file and generating the response.
     *
     * @param string $viewName view file name.
     * @param Throwable $t throwable that is rendered.
     * @param ServerRequestInterface|null $request request, during the processing of which an error occurred.
     * @return ErrorDto
     */
    private function render(string $viewName, Throwable $t, ?ServerRequestInterface $request): ErrorDto
    {
        $view = $this->renderView($viewName, ['throwable' => $t, 'request' => $request]);

        return new ErrorDto($view, $t, [BodyMeta::CONTENT_TYPE => self::CONTENT_TYPE]);
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
        return $this->render($this->common, $t, $request);
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
        return $this->render($this->detailed, $t, $request);
    }
}
