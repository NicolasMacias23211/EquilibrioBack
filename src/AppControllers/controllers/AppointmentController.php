<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\AppointmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AppointmentController
{
    public function __construct(private  AppointmentRepository $citasRepository){}

    public function CreateCita(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        if ($body != null){
           $data = $this->citasRepository->InsertCita($body);
           if ($data){
               $body = json_encode([
                   'message' => 'Cita creada correctamente',
                   'status' => $data
               ]);
               $body = json_encode($body);
               $response->getBody()->write($body);
               $response->withStatus(200);
               return $response->withHeader('Content-Type','application/json');
           }
        }
        $body = json_encode([
            'message' => 'Error creando la cita',
            'status' => false
        ]);
        $body = json_encode($body);
        $response->getBody()->write($body);
        $response->withStatus(500);
        return $response->withHeader('Content-Type','application/json');
    }

    public function AllCitas(Request $request, Response $response):response
    {
        $data = $this->citasRepository->GetAllCitas();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }
}