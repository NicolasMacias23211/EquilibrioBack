<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\ServicesRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class ServicesController
{
    public function __construct(private ServicesRepository $ServiciosRepository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'nombre' => ['required'],
            'apellido' => ['required'],
            'Correo' => ['required'],
            'Documento' => ['required']
            //para integers  'numero' => ['required','integer',['min',1]]
        ]);
    }
    public function getAllServices(Request $request, Response $response):response
    {
        $data = $this->ServiciosRepository->getAllServices();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }



}