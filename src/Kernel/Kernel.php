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

namespace Silence\Kernel;

use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Silence\Config\AppConfig;
use Silence\Config\AppContext;
use Silence\ErrorHandler\ErrorHandler;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Runtime\ApplicationRunnerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Application kernel class.
 *
 * Responsible for assembling the DI container and configuring the application.
 *
 * Launches the application's running algorithm.
 *
 * It uses events that are triggered throughout the execution of the algorithm, so you can subscribe to them and
 * implement some logic.
 * However, this requires a custom event dispatcher extension in the application.
 *
 * It can be launched as follows:
 *
 * ```
 * $config = KernelConfig::withBasePath(dirname(__DIR__, 2))
 *      ->withExtensions([
 *          new RouteExtension(),
 *      ])
 * ;
 * (new Kernel($config))->run();
 * ```
 */
class Kernel implements KernelInterface
{
    protected KernelConfig $config;
    protected ContainerBuilder $container;
    protected EventDispatcherInterface $dispatcher;
    protected EventFactoryInterface $eventFactory;

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function __construct(KernelConfig $config)
    {
        $this->config = $config;

        $this->boot();
    }

    /**
     * The main algorithm for starting and configuring the kernel.
     *
     * Here's what happens:
     *  - basic configuration of the DI container
     *  - loading application settings and context
     *  - connecting application extensions
     *
     * @return void
     *
     * @throws Exception
     */
    protected function boot(): void
    {
        $this->loadDotEnv();

        $this->container = new ContainerBuilder();
        $this->registerContainerParams();

        $loader = new PhpFileLoader($this->container, new FileLocator(__DIR__ . '/Config/Services'));
        $loader->load('services.php');

        $this->applyAppConfig();

        // Reserve error handler during dependency container assembly, extension registration
        $reserveErrorHandler = $this->registerErrorHandler();

        foreach ($this->config->getExtensions() as $extension) {
            $extension->configure($this->container, $this->config);
        }

        $this->container->compile();

        foreach ($this->config->getExtensions() as $extension) {
            $extension->boot($this->container, $this->config);
        }

        // User configured error handler
        $this->registerErrorHandler($reserveErrorHandler);

        $this->initEvents();

        $this->dispatcher->dispatch($this->eventFactory->kernelBooted());
    }

    /**
     * Registers parameters in the container.
     *
     * @return void
     */
    protected function registerContainerParams(): void
    {
        $this->container->setParameter('app.env', $this->getEnvValue());
        $this->container->setParameter('app.base_path', $this->config->getBasePath());
        $this->container->setParameter('app.configs', $this->config->getConfigFiles());
        $this->container->setParameter('app.debug', ($_ENV['APP_DEBUG'] ?? '0') === '1');
    }

    /**
     * Provides Environment value from global state.
     *
     * @return string
     */
    protected function getEnvValue(): string
    {
        return isset($_ENV['APP_ENV']) && is_string($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'prod';
    }

    /**
     * Provides a service from the dependency container, performing an additional check on the return type.
     *
     * @template T of object
     * @param class-string<T> $id
     * @return T&object
     * @throws Exception
     */
    private function service(string $id): object
    {
        $service = $this->container->get($id);
        assert($service instanceof $id);
        return $service;
    }

    /**
     * Fills properties related to event dispatching.
     *
     * @return void
     * @throws Exception
     */
    protected function initEvents(): void
    {
        $this->dispatcher = $this->service(EventDispatcherInterface::class);
        $this->eventFactory = $this->service(EventFactoryInterface::class);
    }

    /**
     * Provides an application runner, which is responsible for executing the application's algorithm.
     *
     * @return ApplicationRunnerInterface
     * @throws Exception
     */
    protected function getApplicationRunner(): ApplicationRunnerInterface
    {
        return $this->service(ApplicationRunnerInterface::class);
    }

    /**
     * Loads .env files, making the information in them available to the application.
     *
     * @return void
     */
    protected function loadDotEnv(): void
    {
        $exists = array_filter($this->config->getDotEnvs(), fn(string $path) => file_exists($path));

        if ($exists === []) {
            return;
        }

        $dotenv = new Dotenv();
        $dotenv->load(...$exists);
    }

    /**
     * Register error handler.
     *
     * @param ErrorHandler|null $old
     * @return ErrorHandler
     * @throws Exception
     */
    protected function registerErrorHandler(?ErrorHandler $old = null): ErrorHandler
    {
        if ($this->container->isCompiled()) {
            // User Defined error handler
            $errorHandler = $this->service(ErrorHandler::class);
        } else {
            // Reserve error handler
            $context = $this->service(AppContext::class);

            $errorHandler = new ErrorHandler($this->service(ThrowableRendererInterface::class));
            $errorHandler->setDebugMode($context->isDebug());
        }

        $old?->disable();

        $errorHandler->register();

        return $errorHandler;
    }

    /**
     * Applies sensitive client application configuration, overwriting the basic backup settings.
     *
     * @return void
     * @throws Exception
     */
    protected function applyAppConfig(): void
    {
        $config = $this->service(AppConfig::class);
        $context = $this->service(AppContext::class);

        if (($locale = $config->get('app.locale')) && is_string($locale)) {
            $context->setLocale($locale);
        }
    }

    /**
     * A method that is executed before the application is launched.
     *
     * @return void
     */
    protected function beforeRun(): void
    {
        $this->dispatcher->dispatch($this->eventFactory->beforeKernelRun());
    }

    /**
     * A method that is executed after the application is launched.
     *
     * @return void
     */
    public function afterRun(): void
    {
        $this->dispatcher->dispatch($this->eventFactory->kernelTerminated());
    }

    /**
     * Launching the application.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->beforeRun();

        $this->getApplicationRunner()->run();

        $this->afterRun();
    }
}
