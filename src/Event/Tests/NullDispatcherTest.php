<?php

declare(strict_types=1);

namespace Silence\Event\Tests;

use PHPUnit\Framework\TestCase;
use Silence\Event\NullDispatcher;
use stdClass;

class NullDispatcherTest extends TestCase
{
    protected function createDispatcher(): NullDispatcher
    {
        return new NullDispatcher();
    }

    public function testDispatchReturnsSameObject(): void
    {
        $event = new stdClass();
        $dispatcher = $this->createDispatcher();

        $this->assertSame(spl_object_hash($event), spl_object_hash($dispatcher->dispatch($event)));
    }
}
