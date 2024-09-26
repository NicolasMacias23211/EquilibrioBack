<?php
declare(strict_types=1);

use AppControllers\controllers\EmployeesController;
use AppControllers\controllers\AuthenticationController;
use AppControllers\controllers\ServicesController;
use AppControllers\controllers\UsersController;
use AppControllers\controllers\AppointmentController;
use Redoc\Redoc;
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

$app->get('/empleados', EmployeesController::class.':AllEmpleados');
$app->get('/users', UsersController::class.':AllUsers');
$app->get('/citas', AppointmentController::class.':AllCitas');
$app->get('/empleados/{id:[0-9]+}', EmployeesController::class.':EmpleadoByid');
$app->post('/empleados',[EmployeesController::class,'CreateEmpleado']);
$app->put('/empleados',[EmployeesController::class,'UploadEmpleado']);
$app->post('/autenticacion', AuthenticationController::class.':validarCredenciales');
$app->get('/servicios', ServicesController::class.':getAllServices');
$app->post('/createNewUser', UsersController::class.':ValidateAndInsertUser');
$app->post('/CreateNewCita', AppointmentController::class.':CreateCita');
$app->get('/documentation', Redoc::class.':getDoc');
$app->get('/', Redoc::class.':getDoc');

$app->run();

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});