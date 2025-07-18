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

use InvalidArgumentException;

/**
 * {@link https://developer.mozilla.org/en-US/docs/Glossary/Quality_values}
 */
final class QualityNegotiator
{
    /**
     * @param array<string, array<string, string>> $valuesWithParams
     * @return array<string, float>
     * @throws InvalidArgumentException
     */
    public function getHeaderValuesWithPriorityParam(array $valuesWithParams): array
    {
        $result = [];
        foreach ($valuesWithParams as $value => $params) {
            $q = $params['q'] ?? $params['Q'] ?? 1.0;

            if (!is_numeric($q) || (float) $q < 0 || (float) $q > 1) {
                throw new InvalidArgumentException('Invalid q value.');
            }

            $result[$value] = (float) $q;
        }

        return $result;
    }

    /**
     * @param array<string, array<string, string>> $valuesWithParams
     * @return list<string>
     * @throws InvalidArgumentException
     */
    public function getSortedHeaderValues(array $valuesWithParams): array
    {
        $valuesWithPriorityParams = $this->getHeaderValuesWithPriorityParam($valuesWithParams);

        uasort($valuesWithPriorityParams, static fn(float $a, float $b): int => $b <=> $a);

        return array_keys($valuesWithPriorityParams);
    }
}
