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

namespace Silence\HttpSpec\HttpCodes;

enum CodeEnum: int
{
    // Informational
    /**
     * The HTTP 100 Continue informational response status code indicates that the initial part of a request has been
     * received and has not yet been rejected by the server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/100}
     */
    case CONTINUE = 100;
    /**
     * The HTTP 101 Switching Protocols informational response status code indicates the protocol that a server has
     * switched to.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/101}
     */
    case SWITCHING_PROTOCOLS = 101;
    /**
     * This code was used in WebDAV contexts to indicate that a request has been received by the server, but no status
     * was available at the time of the response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/102}
     * @deprecated
     */
    case PROCESSING = 102;
    /**
     * The HTTP 103 Early Hints informational response may be sent by a server while it is still preparing a response,
     * with hints about the sites and resources that the server expects the final response will link to.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/103}
     */
    case EARLY_HINTS = 103;

    // Success
    /**
     * The HTTP 200 OK successful response status code indicates that a request has succeeded. A 200 OK response is
     * cacheable by default.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status#informational_responses}
     */
    case OK = 200;
    /**
     * The HTTP 201 Created successful response status code indicates that the HTTP request has led to the creation of
     * a resource.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/201}
     */
    case CREATED = 201;
    /**
     * The HTTP 202 Accepted successful response status code indicates that a request has been accepted for processing,
     * but processing has not been completed or may not have started.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/202}
     */
    case ACCEPTED = 202;
    /**
     * The HTTP 203 Non-Authoritative Information successful response status code indicates that the request was
     * successful, but a transforming proxy has modified the headers or enclosed content from the origin server's
     * 200 (OK) response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/203}
     */
    case NON_AUTHORITATIVE = 203;
    /**
     * There is no content to send for this request, but the headers are useful.
     *
     * The user agent may update its cached headers for this resource with the new ones.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/204}
     */
    case NO_CONTENT = 204;
    /**
     * Tells the user agent to reset the document which sent this request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/205}
     */
    case RESET_CONTENT = 205;
    /**
     * This response code is used in response to a range request when the client has requested a part or parts of a
     * resource.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/206}
     */
    case PARTIAL_CONTENT = 206;
    /**
     * Conveys information about multiple resources, for situations where multiple status codes might be appropriate.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/207}
     */
    case MULTI_STATUS = 207;
    /**
     * Used inside a <dav:propstat> response element to avoid repeatedly enumerating the internal members of
     * multiple bindings to the same collection.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/208}
     */
    case ALREADY_REPORTED = 208;
    /**
     * The server has fulfilled a GET request for the resource, and the response is a representation of the result of
     * one or more instance-manipulations applied to the current instance.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/226}
     */
    case IM_USED = 226;

    // Redirection messages
    /**
     * In agent-driven content negotiation, the request has more than one possible response and the user agent or user
     * should choose one of them.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/300}
     */
    case MULTIPLE_CHOICES = 300;
    /**
     * The URL of the requested resource has been changed permanently. The new URL is given in the response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/301}
     */
    case MOVED_PERMANENTLY = 301;
    /**
     * This response code means that the URI of requested resource has been changed temporarily.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/302}
     */
    case FOUND = 302;
    /**
     * The server sent this response to direct the client to get the requested resource at another URI with a GET request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/303}
     */
    case SEE_OTHER = 303;
    /**
     * This is used for caching purposes.
     *
     * It tells the client that the response has not been modified, so the client can continue to use the same cached
     * version of the response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/304}
     */
    case NOT_MODIFIED = 304;
    /**
     * Defined in a previous version of the HTTP specification to indicate that a requested response must be accessed
     * by a proxy. It has been deprecated due to security concerns regarding in-band configuration of a proxy.
     *
     * @deprecated
     */
    case USE_PROXY = 305;
    /**
     * This response code is no longer used; but is reserved. It was used in a previous version of the HTTP/1.1
     * specification.
     *
     * @deprecated
     */
    case UNUSED = 306;
    /**
     * The server sends this response to direct the client to get the requested resource at another URI with the same
     * method that was used in the prior request.
     *
     * This has the same semantics as the 302 Found response code, with the exception that the user agent must not
     * change the HTTP method used: if a POST was used in the first request, a POST must be used in the redirected request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/307}
     */
    case TEMPORARY_REDIRECT = 307;
    /**
     * This means that the resource is now permanently located at another URI, specified by the Location response header.
     *
     * This has the same semantics as the 301 Moved Permanently HTTP response code, with the exception that the user
     * agent must not change the HTTP method used: if a POST was used in the first request, a POST must be used in the
     * second request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/308}
     */
    case PERMANENT_REDIRECT = 308;

    // Client error responses
    /**
     * The server cannot or will not process the request due to something that is perceived to be a client error.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/400}
     */
    case BAD_REQUEST = 400;
    /**
     * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated".
     *
     * That is, the client must authenticate itself to get the requested response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/401}
     */
    case UNAUTHORIZED = 401;
    /**
     * The initial purpose of this code was for digital payment systems, however this status code is rarely used and
     * no standard convention exists.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/402}
     */
    case PAYMENT_REQUIRED = 402;
    /**
     * The client does not have access rights to the content; that is, it is unauthorized, so the server is refusing to
     * give the requested resource.
     *
     * Unlike 401 Unauthorized, the client's identity is known to the server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/403}
     */
    case FORBIDDEN = 403;
    /**
     * The server cannot find the requested resource.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/404}
     */
    case NOT_FOUND = 404;
    /**
     * The request method is known by the server but is not supported by the target resource.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/405}
     */
    case METHOD_NOT_ALLOWED = 405;
    /**
     * This response is sent when the web server, after performing server-driven content negotiation, doesn't find any
     * content that conforms to the criteria given by the user agent.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/406}
     */
    case NOT_ACCEPTABLE = 406;
    /**
     * This is similar to 401 Unauthorized but authentication is needed to be done by a proxy.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/407}
     */
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    /**
     * This response is sent on an idle connection by some servers, even without any previous request by the client.
     *
     * It means that the server would like to shut down this unused connection.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/408}
     */
    case REQUEST_TIMEOUT = 408;
    /**
     * This response is sent when a request conflicts with the current state of the server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/409}
     */
    case CONFLICT = 409;
    /**
     * This response is sent when the requested content has been permanently deleted from server, with no forwarding address.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/410}
     */
    case GONE = 410;
    /**
     * Server rejected the request because the Content-Length header field is not defined and the server requires it.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/411}
     */
    case LENGTH_REQUIRED = 411;
    /**
     * In conditional requests, the client has indicated preconditions in its headers which the server does not meet.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/412}
     */
    case PRECONDITION_FAILED = 412;
    /**
     * The request body is larger than limits defined by server.
     *
     * The server might close the connection or return an Retry-After header field.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/413}
     */
    case CONTENT_TOO_LARGE = 413;
    /**
     * The URI requested by the client is longer than the server is willing to interpret.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/414}
     */
    case URI_TOO_LONG = 414;
    /**
     * The media format of the requested data is not supported by the server, so the server is rejecting the request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/415}
     */
    case UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * The ranges specified by the Range header field in the request cannot be fulfilled.
     *
     * It's possible that the range is outside the size of the target resource's data.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/416}
     */
    case RANGE_NOT_SATISFIABLE = 416;
    /**
     * This response code means the expectation indicated by the Expect request header field cannot be met by the server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/417}
     */
    case EXPECTATION_FAILED = 417;
    /**
     * Was an April 1 RFC that lampooned the various ways HTTP was abused.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/418}
     */
    case IM_A_TEAPOT = 418;
    /**
     * The request was directed at a server that is not able to produce a response. This can be sent by a server that
     * is not configured to produce responses for the combination of scheme and authority that are included in the request URI.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/421}
     */
    case MISDIRECTED_REQUEST = 421;
    /**
     * The request was well-formed but was unable to be followed due to semantic errors.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/422}
     */
    case UNPROCESSABLE_ENTITY = 422;
    /**
     * The resource that is being accessed is locked.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/423}
     */
    case LOCKED = 423;
    /**
     * The request failed due to failure of a previous request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/424}
     */
    case FAILED_DEPENDENCY = 424;
    /**
     * Indicates that the server is unwilling to risk processing a request that might be replayed.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/425}
     */
    case TOO_EARLY = 425;
    /**
     * The server refuses to perform the request using the current protocol but might be willing to do so after the
     * client upgrades to a different protocol.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/426}
     */
    case PAYLOAD_TOO_LARGE = 426;
    /**
     * The origin server requires the request to be conditional.
     *
     * This response is intended to prevent the 'lost update' problem, where a client GETs a resource's state,
     * modifies it and PUTs it back to the server, when meanwhile a third party has modified the state on the server,
     * leading to a conflict.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/428}
     */
    case PRECONDITION_REQUIRED = 428;
    /**
     * The user has sent too many requests in a given amount of time (rate limiting).
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/429}
     */
    case UNORDERED_LIST = 429;
    /**
     * The server is unwilling to process the request because its header fields are too large.
     *
     * The request may be resubmitted after reducing the size of the request header fields.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/431}
     */
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    /**
     * The user agent requested a resource that cannot legally be provided, such as a web page censored by a government.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/451}
     */
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    // Server errors
    /**
     * The server has encountered a situation it does not know how to handle.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/500}
     */
    case INTERNAL_SERVER_ERROR = 500;
    /**
     * The request method is not supported by the server and cannot be handled.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/501}
     */
    case NOT_IMPLEMENTED = 501;
    /**
     * This error response means that the server, while working as a gateway to get a response needed to handle the
     * request, got an invalid response.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/502}
     */
    case BAD_GATEWAY = 502;
    /**
     * TThe server is not ready to handle the request. Common causes are a server that is down for maintenance or that
     * is overloaded.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/503}
     */
    case SERVICE_UNAVAILABLE = 503;
    /**
     * This error response is given when the server is acting as a gateway and cannot get a response in time.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/504}
     */
    case GATEWAY_TIMEOUT = 504;
    /**
     * The HTTP version used in the request is not supported by the server.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/505}
     */
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    /**
     * The server has an internal configuration error: during content negotiation, the chosen variant is configured to
     * engage in content negotiation itself, which results in circular references when creating responses.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/506}
     */
    case VARIANT_ALSO_NEGOTIATES = 506;
    /**
     * The method could not be performed on the resource because the server is unable to store the representation
     * needed to successfully complete the request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/507}
     */
    case INSUFFICIENT_STORAGE = 507;
    /**
     * The server detected an infinite loop while processing the request.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/508}
     */
    case LOOP_DETECTED = 508;
    /**
     * The client request declares an HTTP Extension (RFC 2774) that should be used to process the request, but the
     * extension is not supported.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/510}
     */
    case NOT_EXTENDED = 510;
    /**
     * Indicates that the client needs to authenticate to gain network access.
     *
     * {@link https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Status/511}
     */
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @return bool
     */
    public function isInformational(): bool
    {
        return $this->value < 200;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->value >= 200 && $this->value < 300;
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->value >= 300 && $this->value < 400;
    }

    /**
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->value >= 400 && $this->value < 500;
    }

    /**
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->value >= 500;
    }

    /**
     * A mapping list of HTTP status codes and the corresponding texts.
     *
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return match ($this) {
            self::CONTINUE => 'Continue',
            self::SWITCHING_PROTOCOLS => 'Switching Protocols',
            self::PROCESSING => 'Processing',
            self::EARLY_HINTS => 'Early Hints',

            self::OK => 'OK',
            self::CREATED => 'Created',
            self::ACCEPTED => 'Accepted',
            self::NON_AUTHORITATIVE => 'Non-Authoritative Information',
            self::NO_CONTENT => 'No Content',
            self::RESET_CONTENT => 'Reset Content',
            self::PARTIAL_CONTENT => 'Partial Content',
            self::MULTI_STATUS => 'Multi-Status',
            self::ALREADY_REPORTED => 'Already Reported',
            self::IM_USED => 'IM Used',

            self::MULTIPLE_CHOICES => 'Multiple Choices',
            self::MOVED_PERMANENTLY => 'Moved Permanently',
            self::FOUND => 'Found',
            self::SEE_OTHER => 'See Other',
            self::NOT_MODIFIED => 'Not Modified',
            self::USE_PROXY => 'Use Proxy',
            self::UNUSED => 'Used',
            self::TEMPORARY_REDIRECT => 'Temporary Redirect',
            self::PERMANENT_REDIRECT => 'Permanent Redirect',

            self::BAD_REQUEST => 'Bad Request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::PAYMENT_REQUIRED => 'Payment Required',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not Found',
            self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
            self::NOT_ACCEPTABLE => 'Not Acceptable',
            self::PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
            self::REQUEST_TIMEOUT => 'Request Timeout',
            self::CONFLICT => 'Conflict',
            self::GONE => 'Gone',
            self::LENGTH_REQUIRED => 'Length Required',
            self::PRECONDITION_FAILED => 'Precondition Failed',
            self::CONTENT_TOO_LARGE => 'Content Too Large',
            self::URI_TOO_LONG => 'URI Too Long',
            self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
            self::RANGE_NOT_SATISFIABLE => 'Range Not Satisfiable',
            self::EXPECTATION_FAILED => 'Expectation Failed',
            self::IM_A_TEAPOT => 'I\'m a teapot',
            self::MISDIRECTED_REQUEST => 'Misdirected Request',
            self::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
            self::LOCKED => 'Locked',
            self::FAILED_DEPENDENCY => 'Failed Dependency',
            self::TOO_EARLY => 'Too Early',
            self::PAYLOAD_TOO_LARGE => 'Payload Too Large',
            self::PRECONDITION_REQUIRED => 'Precondition Required',
            self::UNORDERED_LIST => 'Unordered List',
            self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
            self::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',

            self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
            self::NOT_IMPLEMENTED => 'Not Implemented',
            self::BAD_GATEWAY => 'Bad Gateway',
            self::SERVICE_UNAVAILABLE => 'Service Unavailable',
            self::GATEWAY_TIMEOUT => 'Gateway Timeout',
            self::HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
            self::VARIANT_ALSO_NEGOTIATES => 'Variant Also Negotiates',
            self::INSUFFICIENT_STORAGE => 'Insufficient Storage',
            self::LOOP_DETECTED => 'Loop Detected',
            self::NOT_EXTENDED => 'Not Extended',
            self::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
        };
    }
}
