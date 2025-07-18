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

namespace Silence\HttpSpec\HttpMethods;

/**
 * This is an enumeration containing information about HTTP request methods.
 *
 * HTTP defines a set of request methods to indicate the purpose of the request and what is expected if the request is
 * successful.
 * Although they can also be nouns, these request methods are sometimes referred to as HTTP verbs.
 * Each request method has its own semantics, but some characteristics are shared across multiple methods, specifically
 * request methods can be safe, idempotent, or cacheable.
 *
 * More: {@see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods}
 */
enum MethodEnum: string
{
    /**
     * The <b>GET</b> method requests a representation of the specified resource.
     *
     * Requests using <b>GET</b> should only retrieve data and should not contain a request content.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/GET}
     */
    case HEAD = 'HEAD';
    /**
     * The <b>HEAD</b> HTTP method requests the metadata of a resource in the form of headers that the server would have sent
     * if the <b>GET</b> method was used instead.
     *
     * This method can be used in cases where a URL might produce a large download, for example, a <b>HEAD</b> request can
     * read the Content-Length header to check the file size before downloading the file with a <b>GET</b>.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/HEAD}
     */
    case GET = 'GET';
    /**
     * The <b>POST</b> HTTP method sends data to the server.
     *
     * The type of the body of the request is indicated by the Content-Type header.
     *
     * The difference between <b>PUT</b> and <b>POST</b> is that <b>PUT</b> is idempotent: calling it once is no
     * different from calling it several times successively (there are no side effects).
     * Successive identical <b>POST</b> requests may have additional effects, such as creating the same order several times.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/POST}
     */
    case POST = 'POST';
    /**
     * The <b>PUT</b> HTTP method creates a new resource or replaces a representation of the target resource with the request content.
     *
     * The difference between <b>PUT</b> and <b>POST</b> is that <b>PUT</b> is idempotent: calling it once is no
     * different from calling it several times successively (there are no side effects).
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/PUT}
     */
    case PUT = 'PUT';
    /**
     * The <b>PATCH</b> HTTP method applies partial modifications to a resource.
     *
     * <b>PATCH</b> is somewhat analogous to the "update" concept found in CRUD (in general, HTTP is different from CRUD,
     * and the two should not be confused).
     * In comparison with <b>PUT</b>, a <b>PATCH</b> serves as a set of instructions for modifying a resource, whereas
     * <b>PUT</b> represents a complete replacement of the resource.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/PATCH}
     */
    case PATCH = 'PATCH';
    /**
     * The <b>DELETE</b> HTTP method asks the server to delete a specified resource.
     *
     * The <b>DELETE</b> method has no defined semantics for the message body, so this should be empty.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/DELETE}
     */
    case DELETE = 'DELETE';
    /**
     * The <b>OPTIONS</b> HTTP method requests permitted communication options for a given URL or server.
     *
     * This can be used to test the allowed HTTP methods for a request, or to determine whether a request would succeed
     * when making a CORS preflighted request.
     * A client can specify a URL with this method, or an asterisk (*) to refer to the entire server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Methods/OPTIONS}
     */
    case OPTIONS = 'OPTIONS';

    /**
     * Comparison method enum.
     *
     * Checks whether the transferred value is identical to the current one.
     *
     * @param MethodEnum $method Enum Name.
     * @return bool
     */
    public function is(MethodEnum $method): bool
    {
        return $this === $method;
    }
}
