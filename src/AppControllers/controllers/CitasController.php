<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\CitasRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CitasController
{
    public function __construct(private  CitasRepository $citasRepository){}

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
}