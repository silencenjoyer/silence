<?php

declare(strict_types=1);

namespace Silence\Http\Tests\Handlers;

use Closure;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Silence\Http\Handlers\ClosureHandler;

class ClosureHandlerTest extends TestCase
{
    private bool $handlerCalled = false;
    private ResponseInterface $response;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = $this->createMock(ResponseInterface::class);;
    }

    protected function createHandler(Closure $action, array $requestParameters, array $params): ClosureHandler
    {
        return new ClosureHandler($action, $requestParameters, $params);
    }

    /**
     * @throws Exception
     */
    protected function handle(Closure $action, array $requestParameters = [], array $params = []): void
    {
        $handler = $this->createHandler($action, $requestParameters, $params);
        $request = $this->createMock(ServerRequestInterface::class);

        $handler->handle($request);
    }

    /**
     * @throws Exception
     */
    public function testHandlerInvokesClosureWithParams(): void
    {
        $handler = function (string $company): ResponseInterface {
            $this->handlerCalled = true;
            $this->assertSame($company, 'Silence');
            return $this->response;
        };

        $this->handle(action: $handler, params: ['company' => 'Silence']);

        $this->assertTrue($this->handlerCalled);
    }

    /**
     * @throws Exception
     */
    public function testHandlerInjectsRequestByParameterName(): void
    {
        $handler = function (ServerRequestInterface $request): ResponseInterface {
            $this->handlerCalled = true;
            return $this->createMock(ResponseInterface::class);
        };

        $this->handle($handler, ['request']);

        $this->assertTrue($this->handlerCalled);
    }

    /**
     * @throws Exception
     */
    public function testHandlerCombinesParamsAndRequest(): void
    {
        $handler = function (ServerRequestInterface $req, string $company): ResponseInterface {
            $this->handlerCalled = true;
            $this->assertSame($company, 'Silence');
            return $this->response;
        };

        $this->handle($handler, ['req'], ['company' => 'Silence']);

        $this->assertTrue($this->handlerCalled);
    }
}
