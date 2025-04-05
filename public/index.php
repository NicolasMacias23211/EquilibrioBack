<?php
declare(strict_types=1);

use AppControllers\controllers\membersController;
use AppControllers\controllers\AuthenticationController;
use AppControllers\controllers\ServicesController;
use AppControllers\controllers\ScheduleController;
use Redoc\Redoc;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\MiddlewareResponseHeader;

define('App_rott',dirname(__DIR__));
require App_rott.'/vendor/autoload.php';

//Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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


$app->get('/documentation', Redoc::class.':getDoc');
$app->get('/', Redoc::class.':getDoc');
$app->get('/professionals', membersController::class.':allProfessionals');
$app->post('/createProfessional' ,membersController::class.':CreateProfessional');
$app->post('/autenticacion', AuthenticationController::class.':validarCredenciales');
$app->post('/createMember', membersController::class.':createNewMember');
$app->get('/servicios', ServicesController::class.':getAllServices');
$app->get('/schedule/{document}', [ScheduleController::class, 'getScheduleByPerson']);

$app->run();

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});