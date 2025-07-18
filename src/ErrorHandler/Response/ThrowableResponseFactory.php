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

namespace Silence\ErrorHandler\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\ErrorHandler\ErrorDto;
use Silence\ErrorHandler\ErrorHandler;
use Silence\ErrorHandler\RendererResolvers\RendererResolverInterface;
use Throwable;

class ThrowableResponseFactory implements ThrowableResponseFactoryInterface
{
    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected RendererResolverInterface $rendererResolver,
        protected ErrorHandler $errorHandler,
    ) {
    }

    /**
     * Modifies response with throwable data.
     *
     * @param ResponseInterface $response
     * @param ErrorDto $errorDto
     * @return ResponseInterface
     */
    protected function modifyResponse(ResponseInterface $response, ErrorDto $errorDto): ResponseInterface
    {
        foreach ($errorDto->headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        $response->getBody()->write((string) $errorDto);

        return $response;
    }

    /**
     * Handles Throwable.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $e
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, Throwable $e): ResponseInterface
    {
        $renderer = $this->rendererResolver->resolve($request);
        $errorDto = $this->errorHandler->handle($e, $request, $renderer);

        $response = $this->responseFactory->createResponse($errorDto->getStatusCode()->value);
        return $this->modifyResponse($response, $errorDto);
    }
}
