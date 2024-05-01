<?php
declare(strict_types=1);
namespace App\Middleware;

use AppRepository\Repositories\EmpleadosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use slim\Exception\HttpNotFoundException;
class GetEmpleados
{
    public function __construct(private EmpleadosRepository $empleadosRepository){}
    public function __invoke(Request $request,RequestHandler $handler) : Response
    {
        $context = RouteContext::fromRequest($request);
        $route = $context->getRoute();
        $id = $route->getArgument('id');
        $data = $this->empleadosRepository->GetEmpleadoById((int)$id);
        if ($data === false){
            throw new HttpNotFoundException(
                $request,
                message: 'Produc not foud'
            );
        }
        $request = $request->withAttribute('empelado',$data);
        return $handler->handle($request);
    }
}
