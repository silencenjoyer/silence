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

namespace Silence\HeaderParser\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Silence\HeaderParser\HeaderParser;

class HeaderParserTest extends TestCase
{
    private HeaderParser $parser;

    protected function createParser(): HeaderParser
    {
        return new HeaderParser();
    }

    protected function setUp(): void
    {
        $this->parser = $this->createParser();
    }

    /**
     * @return list<list<array<string, string>>, string>
     */
    public static function parseParamsProvider(): array
    {
        return [
            [['q' => '0.9'], 'application/xml;q=0.9'],

            [['v' => 'b3', 'q' => '0.7'], 'application/signed-exchange;v=b3;q=0.7'],

            [[], 'image/webp'],

            [[], ''],
        ];
    }

    public function testGetHeaderValues(): void
    {
        $input = [
            'text/html,application/xhtml+xml',
            'application/xml;q=0.9,image/avif',
            'image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        ];

        $expected = [
            'text/html',
            'application/xhtml+xml',
            'application/xml;q=0.9',
            'image/avif',
            'image/webp',
            'image/apng',
            '*/*;q=0.8',
            'application/signed-exchange;v=b3;q=0.7',
        ];

        $this->assertSame($expected, $this->parser->getHeaderValues($input));
    }

    #[DataProvider('parseParamsProvider')]
    public function testParseParams(array $expected, string $input): void
    {
        $this->assertSame($expected, $this->parser->parseParams($input));
    }

    public function testGetHeaderValuesWithParams(): void
    {
        $input = [
            'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        ];

        $expected = [
            'text/html' => [],
            'application/xhtml+xml' => [],
            'application/xml' => ['q' => '0.9'],
            'image/avif' => [],
            'image/webp' => [],
            'image/apng' => [],
            '*/*' => ['q' => '0.8'],
            'application/signed-exchange' => ['v' => 'b3', 'q' => '0.7'],
        ];

        $this->assertSame($expected, $this->parser->getHeaderValuesWithParams($input));
    }
}
