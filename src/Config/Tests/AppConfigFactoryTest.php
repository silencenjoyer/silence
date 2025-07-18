<?php

declare(strict_types=1);

namespace Silence\Config\Tests;

use PHPUnit\Framework\TestCase;
use Silence\Config\AppConfig;
use Silence\Config\AppConfigFactory;

class AppConfigFactoryTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpDir = sys_get_temp_dir() . '/app_config_factory_test';
        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        $files = glob($this->tmpDir . '/*.php');
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($this->tmpDir);

        parent::tearDown();
    }

    protected function createConfigFile(string $name, array $data): void
    {
        file_put_contents(
            $this->tmpDir . '/' . $name . '.php',
            '<?php return ' . var_export($data, true) . ';'
        );
    }

    public function testCreatesConfigWithSingleFile(): void
    {
        $this->createConfigFile('main', ['first' => 1]);

        $config = AppConfigFactory::create('dev', $this->tmpDir, ['main']);
        $this->assertInstanceOf(AppConfig::class, $config);
        $this->assertSame(1, $config->get('first'));
    }


    public function testCreatesConfigWithEnvironmentOverride(): void
    {
        $this->createConfigFile('main', ['first' => 1, 'second' => 2]);
        $this->createConfigFile('main_dev', ['second' => 3]);

        $config = AppConfigFactory::create('dev', $this->tmpDir, ['main']);

        $this->assertSame(1, $config->get('first'));
        $this->assertSame(3, $config->get('second')); //  Must be overridden by dev env
    }

    public function testSkipsMissingFilesWithoutError(): void
    {
        $this->createConfigFile('main', ['first' => 1]);

        $config = AppConfigFactory::create('dev', $this->tmpDir, ['main', 'missing']);

        $this->assertSame(1, $config->get('first'));
        $this->assertNull($config->get('second'));
    }

    public function testMergesMultipleFiles(): void
    {
        $this->createConfigFile('first', ['x' => 1]);
        $this->createConfigFile('second', ['y' => 2]);
        $this->createConfigFile('first_dev', ['x' => 3]);

        $config = AppConfigFactory::create('dev', $this->tmpDir, ['first', 'second']);

        $this->assertSame(3, $config->get('x')); // Must be overridden by dev env
        $this->assertSame(2, $config->get('y'));
    }
}
