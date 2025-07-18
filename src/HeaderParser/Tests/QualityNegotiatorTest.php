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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Silence\HeaderParser\QualityNegotiator;

class QualityNegotiatorTest extends TestCase
{
    private QualityNegotiator $negotiator;

    protected function createNegotiator(): QualityNegotiator
    {
        return new QualityNegotiator();
    }

    protected function setUp(): void
    {
        $this->negotiator = $this->createNegotiator();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetHeaderValuesWithPriorityParamDefaultFilling(): void
    {
        $input = [
            'text/html' => [],
            'application/json' => ['q' => '1'],
        ];

        $expected = [
            'text/html' => 1.0,
            'application/json' => 1.0,
        ];

        $this->assertSame($expected, $this->negotiator->getHeaderValuesWithPriorityParam($input));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetHeaderValuesWithPriorityParamWithExplicitQ(): void
    {
        $input = [
            'application/xml' => ['q' => '0.9'],
            'image/webp' => ['Q' => '0.5'],
        ];

        $expected = [
            'application/xml' => 0.9,
            'image/webp' => 0.5,
        ];

        $this->assertSame($expected, $this->negotiator->getHeaderValuesWithPriorityParam($input));
    }

    public function testGetHeaderValuesWithPriorityParamInvalidQThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid q value.');

        $input = [
            'text/html' => ['q' => 'invalid'],
        ];

        $this->negotiator->getHeaderValuesWithPriorityParam($input);
    }

    public function testGetHeaderValuesWithPriorityParamOutOfRangeQThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid q value.');

        $input = [
            'text/html' => ['q' => '1.5'],
        ];

        $this->negotiator->getHeaderValuesWithPriorityParam($input);
    }

    public function testGetSortedHeaderValues(): void
    {
        $input = [
            'text/html' => ['q' => '0.8'],
            'application/json' => ['q' => '1.0'],
            'image/png' => ['q' => '0.4'],
        ];

        $expected = [
            'application/json',
            'text/html',
            'image/png',
        ];

        $this->assertSame($expected, $this->negotiator->getSortedHeaderValues($input));
    }

    public function testGetSortedHeaderValuesWithMissingQ(): void
    {
        $input = [
            'text/html' => [],
            'application/json' => ['q' => '0.5'],
            'image/png' => [],
        ];

        $expected = [
            'text/html',      // q = 1.0
            'image/png',      // q = 1.0
            'application/json', // q = 0.5
        ];

        $this->assertSame($expected, $this->negotiator->getSortedHeaderValues($input));
    }
}
