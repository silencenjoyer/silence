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
 * The class contains HTTP header constants that refer to meta information about the body of an incoming request.
 */
final class BodyMeta
{
    /**
     * The HTTP Content-Type representation header is used to indicate the original media type of resource before
     * any content encoding is applied.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Type}
     */
    public const string CONTENT_TYPE = 'Content-Type';
    /**
     * The HTTP Content-Length header indicates the size, in bytes, of the message body sent to the recipient.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Length}
     */
    public const string CONTENT_LENGTH = 'Content-Length';
    /**
     * The HTTP Content-Language representation header is used to describe the language(s) intended for the audience,
     * so users can differentiate it according to their own preferred language.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Language}
     */
    public const string CONTENT_LANGUAGE = 'Content-Language';
    /**
     * The HTTP Content-Disposition header indicates whether content should be displayed inline in the browser as a
     * web page or part of a web page or downloaded as an attachment locally.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Disposition}
     */
    public const string CONTENT_DISPOSITION = 'Content-Disposition';
    /**
     * The HTTP Content-Encoding representation header lists the encodings and the order in which they have been
     * applied to a resource.
     *
     * This lets the recipient know how to decode the data in order to obtain the original content format described in
     * the Content-Type header.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Encoding}
     */
    public const string CONTENT_ENCODING = 'Content-Encoding';
}
