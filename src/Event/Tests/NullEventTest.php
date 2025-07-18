<?php

declare(strict_types=1);

namespace Silence\Event\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Silence\Event\NullEvent;
use Silence\Event\Types\BeforeKernelRunInterface;
use Silence\Event\Types\KernelBootedInterface;
use Silence\Event\Types\KernelTerminatedInterface;
use Silence\Event\Types\OnResponseInterface;
use Silence\Event\Types\RequestCreatedInterface;
use Silence\Event\Types\RouteNotFoundInterface;
use Silence\Event\Types\RouteResolvedInterface;

class NullEventTest extends TestCase
{
    protected NullEvent $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->event = new NullEvent();
    }

    public static function methodDataProvider(): array
    {
        $reflection = new ReflectionClass(NullEvent::class);

        return array_map(
            fn(ReflectionMethod $method): array => [$method->getName()],
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                fn(ReflectionMethod $method): bool => $method->getDeclaringClass()->getName() === NullEvent::class
            )
        );
    }

    public function testImplementsInterfaces(): void
    {
        $this->assertInstanceOf(KernelBootedInterface::class, $this->event);
        $this->assertInstanceOf(BeforeKernelRunInterface::class, $this->event);
        $this->assertInstanceOf(RequestCreatedInterface::class, $this->event);
        $this->assertInstanceOf(RouteResolvedInterface::class, $this->event);
        $this->assertInstanceOf(RouteNotFoundInterface::class, $this->event);
        $this->assertInstanceOf(OnResponseInterface::class, $this->event);
        $this->assertInstanceOf(KernelTerminatedInterface::class, $this->event);
    }

    #[DataProvider('methodDataProvider')]
    public function testMethodsThrowException(string $method): void
    {
        $this->expectExceptionMessage('Null event is a reserve class. Did you forget to register the event factory?');

        $this->event->$method();
    }
}
