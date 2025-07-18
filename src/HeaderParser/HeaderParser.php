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

namespace Silence\HeaderParser;

/**
 * Class for parsing HTTP headers.
 *
 * Convenient when used in conjunction with a PSR request.
 */
final class HeaderParser
{
    /**
     *
     * Example:
     * ```
     * $headerParser->getHeaderValues($request->getHeader('Accept'));
     *
     * array (
     * 0 => 'text/html',
     * 1 => 'application/xhtml+xml',
     * 2 => 'application/xml;q=0.9',
     * 3 => 'image/avif',
     * 4 => 'image/webp',
     * 5 => 'image/apng',
     * 6 => '*\/*;q = 0.8',
     * 7 => 'application/signed-exchange;v=b3;q=0.7',
     * )
     * ```
     *
     * @param array<string> $headerData
     * @return list<string>
     */
    public function getHeaderValues(array $headerData): array
    {
        $list = [];

        foreach ($headerData as $header) {
            $list = [...$list, ...explode(',', trim($header))];
        }

        return $list;
    }

    /**
     *
     * Example:
     * ```
     * $headerParser->parseParams('application/xml;q=0.9');
     *
     * array (
     * 'q' => '0.9',
     * )
     * ```
     *
     * @param string $headerParams
     * @return array<string, string>
     */
    public function parseParams(string $headerParams): array
    {
        preg_match_all('/([a-z]+)=([^;]+)/', $headerParams, $matches);

        return array_combine($matches[1], $matches[2]);
    }

    /**
     *
     * Example:
     * ```
     * $headerParser->getHeaderValuesWithParams(['text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.7']);
     *
     * array (
     * 'text/html' =>
     * array (
     * ),
     * 'application/xhtml+xml' =>
     * array (
     * ),
     * 'application/xml' =>
     * array (
     * 'q' => '0.9',
     * ),
     * 'image/avif' =>
     * array (
     * ),
     * 'image/webp' =>
     * array (
     * ),
     * 'image/apng' =>
     * array (
     * ),
     * '*\/*' =>
     * array (
     * 'q' => '0.8',
     * ),
     * 'application/signed-exchange' =>
     * array (
     * 'v' => 'b3',
     * 'q' => '0.7',
     * ),
     * )
     * ```
     * @param array<string> $headerData
     * @return array<string, array<string, string>>
     */
    public function getHeaderValuesWithParams(array $headerData): array
    {
        $allValues = $this->getHeaderValues($headerData);

        $result = [];
        foreach ($allValues as $value) {
            $headerParams = explode(';', $value, 2);
            $headerValue = array_shift($headerParams);

            if ($headerParams === []) {
                $result[$headerValue] = [];
            } else {
                $result[$headerValue] = $this->parseParams($value);
            }
        }

        return $result;
    }
}
