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

namespace Silence\Views;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface for view files rendering.
 *
 * The implementation must be able to process the contents of templates using the parameters passed
 * and return an instance of an HTTP response.
 */
interface ViewRendererInterface
{
    /**
     * Template rendering.
     *
     * @param string $view
     * @param array<string, mixed> $params
     * @return ResponseInterface
     */
    public function render(string $view, array $params = []): ResponseInterface;
}
