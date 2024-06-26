<?php
declare(strict_types=1);

use AppControllers\controllers\EmpleadosController;
use AppControllers\controllers\AutenticacionController;
use AppControllers\controllers\ServiciosController;
use AppControllers\controllers\UsuariosController;
use AppControllers\controllers\CitasController;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\MiddlewareResponseHeader;

define('App_rott',dirname(__DIR__));
require App_rott.'/vendor/autoload.php';

$builder = new ContainerBuilder;
$cotainer = $builder->addDefinitions(App_rott.'/config/Definitions.php')->build();
AppFactory::setContainer($cotainer);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true,true,true);
$errorHandle = $errorMiddleware->getDefaultErrorHandler();
$errorHandle->forceContentType('application/json');
$app->add(new MiddlewareResponseHeader);
$collector = $app->getRouteCollector();
$app->addRoutingMiddleware();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs());

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->get('/empleados', EmpleadosController::class.':AllEmpleados');
$app->get('/users', UsuariosController::class.':AllUsers');
$app->get('/citas', CitasController::class.':AllCitas');
$app->get('/empleados/{id:[0-9]+}', EmpleadosController::class.':EmpleadoByid')->add(App\Middleware\GetEmpleados::class);
$app->post('/empleados',[EmpleadosController::class,'CreateEmpleado']);
$app->post('/autenticacion', AutenticacionController::class.':validarCredenciales');
$app->get('/servicios', ServiciosController::class.':getAllServices');
$app->post('/createNewUser', UsuariosController::class.':ValidateAndInsertUser');
$app->post('/CreateNewCita', CitasController::class.':CreateCita');

$app->run();

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});