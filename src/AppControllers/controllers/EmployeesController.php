<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\EmployeesRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class EmployeesController
{
    public function __construct(private EmployeesRepository $EmployeesRepository , private Validator $validator)
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
        $data = $this->EmployeesRepository->GetAllEmpleados();
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
        $id = $this->EmployeesRepository->Create($body);
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
        $body = $request->getParsedBody();
        $data = $this->EmployeesRepository->GetEmpleadoById($body['IdEmpleado']);
        $responseArray = json_decode($data, true);
        if($responseArray['success']) {
            $updateResult = $this->EmployeesRepository->UpdateEmployee($body);
            $data = json_decode($updateResult, true);
            if($data['success']) {
                $response->getBody()->write($updateResult);
                return $response->withStatus(200);
            }
        }
        $response->getBody()->write(json_encode(['success' => false, 'Message' => 'Error actualizando el empleado']));
        return $response->withStatus(500);
    }

}