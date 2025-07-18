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

namespace Silence\Http\Request;

use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Implementation of HTTP request assembly for the Nyholm\Psr7 package.
 */
readonly class NyholmRequestFactory implements RequestFactoryInterface
{
    public function __construct(
        private ServerRequestFactoryInterface $serverRequestFactory,
        private UriFactoryInterface $uriFactory,
        private UploadedFileFactoryInterface $uploadedFileFactory,
        private StreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return ServerRequestInterface
     */
    public function create(): ServerRequestInterface
    {
        return (new ServerRequestCreator(
            $this->serverRequestFactory,
            $this->uriFactory,
            $this->uploadedFileFactory,
            $this->streamFactory)
        )->fromGlobals();
    }
}
