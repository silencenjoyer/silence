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

namespace Silence\Http\Handlers;

use Closure;

/**
 * Interface for {@see ClosureHandler} factories.
 */
interface ClosureHandlerFactoryInterface
{
    /**
     * Must create handler.
     *
     * @param Closure $action
     * @param list<string> $requestParameters
     * @param array<array-key, mixed> $params
     * @return ClosureHandler
     */
    public function create(Closure $action, array $requestParameters, array $params = []): ClosureHandler;
}
