<?php

declare(strict_types=1);

namespace Silence\Config\Tests;

use PHPUnit\Framework\TestCase;
use Silence\Config\AppContext;
use Silence\Config\AppContextFactory;

class AppContextFactoryTest extends TestCase
{
    public function testFactoryCreatesAppContext(): void
    {
        $context = AppContextFactory::create('prod', true);

        $this->assertInstanceOf(AppContext::class, $context);
        $this->assertSame('prod', $context->getEnv());
        $this->assertTrue($context->isDebug());
    }
}
