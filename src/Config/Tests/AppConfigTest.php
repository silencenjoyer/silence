<?php

declare(strict_types=1);

namespace Silence\Config\Tests;

use PHPUnit\Framework\TestCase;
use Silence\Config\AppConfig;

class AppConfigTest extends TestCase
{
    public function createConfig(array $config = []): AppConfig
    {
        return new AppConfig($config);
    }

    public function testSetWithSimplePath(): void
    {
        $config = $this->createConfig();
        $config->set('company.name', 'Silence');

        $this->assertEquals(['name' => 'Silence'], $config->get('company'));
    }

    public function testSetWithEscapedDot(): void
    {
        $config = $this->createConfig();
        $config->set('company\.name', 'Silence');

        $this->assertEquals('Silence', $config->get('company\.name'));
    }

    public function testSetWithDeepNesting(): void
    {
        $config = $this->createConfig();
        $config->set('app.database.connections.mysql.host', 'localhost');

        $this->assertEquals('localhost', $config->get('app.database.connections.mysql.host'));
    }

    public function testSetOverwritesExistingValues(): void
    {
        $config = $this->createConfig();
        $config->set('app.name', 'OldName');
        $config->set('app.name', 'NewName');

        $this->assertEquals('NewName', $config->get('app.name'));
    }

    public function testGetWithSimplePath(): void
    {
        $config = $this->createConfig([
            'company' => [
                'name' => 'Silence',
            ]
        ]);

        $this->assertEquals('Silence', $config->get('company.name'));
    }

    public function testGetWithEscapedDot(): void
    {
        $config = $this->createConfig(['company.name' => 'Silence']);

        $this->assertEquals('Silence', $config->get('company\.name'));
    }

    public function testGetWithDeepNesting(): void
    {
        $config = $this->createConfig([
            'app' => [
                'database' => [
                    'connections' => [
                        'mysql' => [
                            'host' => 'localhost',
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals('localhost', $config->get('app.database.connections.mysql.host'));
    }

    public function testGetReturnsDefaultForNonExistentPath(): void
    {
        $config = $this->createConfig();
        $this->assertNull($config->get('non.existent.path'));
        $this->assertEquals('default', $config->get('non.existent.path', 'default'));
    }

    public function testGetWithMixedPathSegments(): void
    {
        $config = $this->createConfig([
            'app' => [
                'db.config' => [
                    'host' => 'localhost',
                ]
            ]
        ]);

        $this->assertEquals('localhost', $config->get('app.db\.config.host'));
    }

    public function testFluentInterface(): void
    {
        $config = $this->createConfig();
        $result = $config->set('key', 'value');

        $this->assertInstanceOf(AppConfig::class, $result);
    }
}
