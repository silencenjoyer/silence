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

namespace Silence\Http\HandlerResolvers;

use Psr\Http\Server\RequestHandlerInterface;
use Silence\Routing\Matcher\MatchedRoute;

/**
 * Handler resolver interface.
 *
 * Its implementations will be responsible for assembling handlers.
 */
interface HandlerResolverInterface
{
    /**
     * The method should assemble the final handler based on the resolved route.
     *
     * @param MatchedRoute $resolved
     * @return RequestHandlerInterface
     */
    public function resolve(MatchedRoute $resolved): RequestHandlerInterface;
}
