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
 * Base factory for {@see ClosureHandler} creation.
 */
class ClosureHandlerFactory implements ClosureHandlerFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Closure $action, array $requestParameters, array $params = []): ClosureHandler
    {
        return new ClosureHandler($action, $requestParameters, $params);
    }
}
