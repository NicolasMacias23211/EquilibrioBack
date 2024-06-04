<?php
declare(strict_types=1);
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class MiddlewareResponseHeader
{
    public function __invoke(Request $request,RequestHandler $handler) : Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
        $Response = $handler->handle($request);
        return $Response->withHeader('Content-Type','application/json')->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', $requestHeaders)
        ->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    }
}
