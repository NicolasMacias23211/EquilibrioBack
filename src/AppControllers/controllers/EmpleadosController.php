<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\EmpleadosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class EmpleadosController
{
    public function __construct(private EmpleadosRepository $empleadosRepository,private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'nombre' => ['required'],
            'apellido' => ['required'],
            'Correo' => ['required'],
            'Documento' => ['required']
            //para integers  'numero' => ['required','integer',['min',1]]
        ]);
    }
    public function AllEmpleados(Request $request, Response $response):response
    {
        $data = $this->empleadosRepository->GetAllEmpleados();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }

    public function EmpleadoByid(Request $request, Response $response,String $id):response
    {
        $data = $request->getAttribute('empelado');
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }
    public function CreateEmpleado(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $this->validator = $this->validator->withData($body);
        if (! $this->validator->validate()){
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response ->withStatus(422);
        }
        $id = $this->empleadosRepository->Create($body);
        $body = json_encode([
            'message' => 'Empleado registrdo exitosamente',
            'id' => $id
        ]);
        $body = json_encode($body);
        $response->getBody()->write($body);
        return $response->withStatus(201);
    }
    public function UploadEmpleado(Request $request, Response $response):response
    {
        return $response;
    }

}