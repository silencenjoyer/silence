<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Silence\Config\AppConfig;
use Silence\Config\AppConfigFactory;
use Silence\Config\AppContext;
use Silence\Config\AppContextFactory;
use Silence\ErrorHandler\ErrorHandler;
use Silence\ErrorHandler\Middlewares\ExceptionHandlerMiddlewareInterface;
use Silence\ErrorHandler\Middlewares\ThrowableMiddleware;
use Silence\ErrorHandler\RendererResolvers\ContentTypeRendererResolver;
use Silence\ErrorHandler\RendererResolvers\RendererResolverInterface;
use Silence\ErrorHandler\Renderers\HtmlRenderer;
use Silence\ErrorHandler\Renderers\JsonRenderer;
use Silence\ErrorHandler\Renderers\ThrowableRendererInterface;
use Silence\ErrorHandler\Response\ThrowableResponseFactory;
use Silence\ErrorHandler\Response\ThrowableResponseFactoryInterface;
use Silence\Event\EventFactoryInterface;
use Silence\Event\NullDispatcher;
use Silence\Event\NullEventFactory;
use Silence\HeaderParser\HeaderParser;
use Silence\HeaderParser\QualityNegotiator;
use Silence\Http\Emitters\Emitter;
use Silence\Http\Emitters\EmitterInterface;
use Silence\Http\HandlerResolvers\ClosureResolver;
use Silence\Http\HandlerResolvers\HandlerResolverInterface;
use Silence\Http\Handlers\ClosureHandlerFactory;
use Silence\Http\Handlers\ClosureHandlerFactoryInterface;
use Silence\Http\Handlers\MiddlewareRunnerFactory;
use Silence\Http\Handlers\MiddlewareRunnerFactoryInterface;
use Silence\Http\Handlers\RouteHandler;
use Silence\Http\Handlers\RouteHandlerInterface;
use Silence\Http\Request\NyholmRequestFactory;
use Silence\Http\Request\RequestFactoryInterface as AppRequestFactoryInterface;
use Silence\Routing\Matcher\HttpMatcher;
use Silence\Routing\Matcher\MatcherInterface;
use Silence\Routing\RouteProviders\RouteProviderRegistry;
use Silence\Routing\Router;
use Silence\Routing\RouterInterface;
use Silence\Runtime\ApplicationRunnerInterface;
use Silence\Runtime\HttpApplicationRunner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    // Container configuration
    $services = $container->services()
        ->defaults()
            ->public()
            ->autowire()
            ->autoconfigure()
    ;

    $appPath = dirname(__DIR__, 5) . '/app';
    $services->load('App\\', $appPath)
        ->exclude([$appPath . '/Bootstrap', $appPath . '/Config'])
    ;
    // ===========================================================================

    // Application Config
    $services->set(AppConfig::class)
        ->factory([AppConfigFactory::class, 'create'])
        ->args(['%app.env%', '%app.base_path%/app/Config', '%app.configs%'])
    ;
    // ===========================================================================

    // Application Context
    $services->set(AppContext::class)
        ->factory([AppContextFactory::class, 'create'])
        ->args(['%app.env%', '%app.debug%'])
    ;
    // ===========================================================================

    // Container self-alias
    $services->alias(ContainerInterface::class, new Reference('service_container'));
    // ===========================================================================

    // Route Registry
    $services->set(RouteProviderRegistry::class, RouteProviderRegistry::class);
    // ===========================================================================

    // Event system
    $services->set(EventFactoryInterface::class, NullEventFactory::class);
    $services->set(EventDispatcherInterface::class, NullDispatcher::class);
    // ===========================================================================

    // HTTP, PSR-7
    $services->set(Psr17Factory::class)
        ->alias(RequestFactoryInterface::class, Psr17Factory::class)
        ->alias(ResponseFactoryInterface::class, Psr17Factory::class)
        ->alias(ServerRequestFactoryInterface::class, Psr17Factory::class)
        ->alias(StreamFactoryInterface::class, Psr17Factory::class)
        ->alias(UploadedFileFactoryInterface::class, Psr17Factory::class)
        ->alias(UriFactoryInterface::class, Psr17Factory::class)
    ;

    $services->set(AppRequestFactoryInterface::class, NyholmRequestFactory::class);
    // ===========================================================================

    // Application Runner
    $services->set(ApplicationRunnerInterface::class, HttpApplicationRunner::class);
    // ===========================================================================

    // Routing, Handler Resolvers, Request Handlers
    $services->set(MiddlewareRunnerFactoryInterface::class, MiddlewareRunnerFactory::class);
    $services->set(ClosureHandlerFactoryInterface::class, ClosureHandlerFactory::class);
    $services->set(MatcherInterface::class, HttpMatcher::class);
    $services->set(RouterInterface::class, Router::class);
    $services->set(RouteHandlerInterface::class, RouteHandler::class);
    $services->set(HandlerResolverInterface::class, ClosureResolver::class);
    $services->set(MiddlewareRunnerFactory::class, MiddlewareRunnerFactory::class);
    $services->set(ExceptionHandlerMiddlewareInterface::class, ThrowableMiddleware::class);
    // ===========================================================================

    // Emitter
    $services->set(EmitterInterface::class, Emitter::class);
    // ===========================================================================

    // Error Renderer
    $services->set(HtmlRenderer::class, HtmlRenderer::class)
        ->alias(ThrowableRendererInterface::class, HtmlRenderer::class)
    ;
    $services->set(JsonRenderer::class, JsonRenderer::class);

    $services->set(ErrorHandler::class, ErrorHandler::class)->call('setDebugMode', ['%app.debug%']);
    $services->set(RendererResolverInterface::class, ContentTypeRendererResolver::class);

    $services->set(ThrowableResponseFactoryInterface::class, ThrowableResponseFactory::class);
    // ===========================================================================

    // Header Parser
    $services->set(HeaderParser::class, HeaderParser::class);
    $services->set(QualityNegotiator::class, QualityNegotiator::class);
    // ===========================================================================

    // PSR-3 Logger
    $services->set(LoggerInterface::class, NullLogger::class);
    // ===========================================================================
};
