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

namespace Silence\HttpSpec\HttpHeaders;

/**
 * The class contains HTTP header constants that relate to meta information about the sender's expectations.
 */
final class ContentNegotiation
{
    /**
     * The HTTP Accept request and response header indicates which content types, expressed as MIME types,
     * the sender is able to understand.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Accept}
     */
    public const string ACCEPT = 'Accept';
    /**
     * The HTTP Accept-Encoding request and response header indicates the content encoding
     * (usually a compression algorithm) that the sender can understand.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Accept-Encoding}
     */
    public const string ACCEPT_ENCODING = 'Accept-Encoding';
    /**
     * The accept-charset header specifies the character encodings which are accepted by the client and this header
     * also allows a user-agent to specify the charsets it supports.
     *
     * {@link https://www.geeksforgeeks.org/computer-networks/http-headers-accept-charset/}
     */
    public const string ACCEPT_CHARSET = 'Accept-Charset';
    /**
     * The HTTP Accept-Language request header indicates the natural language and locale that the client prefers.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Accept-Language}
     */
    public const string ACCEPT_LANGUAGE = 'Accept-Language';
}
