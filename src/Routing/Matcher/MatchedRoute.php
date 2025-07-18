<?php

/*
 * This file is part of the Silence package.
 *
 * (c) Andrew Gebrich <an_gebrich@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this
 * source code.
 */

declare (strict_types = 1);

namespace Silence\Routing\Matcher;

use Silence\Routing\RouteInterface;

/**
 * DTO for the matched route.
 */
readonly class MatchedRoute
{
    /**
     * @param RouteInterface $route matched route.
     * @param array<array-key, mixed> $parameters matched route parameters.
     */
    public function __construct(
        public RouteInterface $route,
        public array $parameters = [],
    ) {
    }
}
