<?php

declare(strict_types=1);

namespace Silence\Config\Tests;

use PHPUnit\Framework\TestCase;
use Silence\Config\AppContext;

class AppContextTest extends TestCase
{
    public function testDefaults(): void
    {
        $context = new AppContext();

        $this->assertSame(AppContext::DEFAULT_ENV, $context->getEnv());
        $this->assertFalse($context->isDebug());
        $this->assertSame(AppContext::FALLBACK_LOCALE, $context->getLocale());
    }

    public function testCustomValues(): void
    {
        $context = new AppContext('prod', true, 'uk_UA');

        $this->assertSame('prod', $context->getEnv());
        $this->assertTrue($context->isDebug());
        $this->assertSame('uk_UA', $context->getLocale());
    }

    public function testSetLocale(): void
    {
        $context = new AppContext();

        $context->setLocale('fr_FR');

        $this->assertSame('fr_FR', $context->getLocale());
    }
}
