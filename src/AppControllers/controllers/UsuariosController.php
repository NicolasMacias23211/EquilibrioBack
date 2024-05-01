<?php

namespace AppControllers\controllers;

use AppRepository\Repositories\UsuariosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class UsuariosController
{
    public function __construct(private UsuariosRepository $usuariosRepository)
    {
        $this->validator->mapFieldsRules([
            'antecendetesQuirurgicos' => ['required'],
            'antecendetesFarmaceuticos' => ['required'],
            'antecendetesToxicos' => ['required'],
            'antecendetesFamiliares' => ['required'],
            'bebe' => ['required'],
            'fuma' => ['required'],
            'ejercisio' => ['required']
        ]);

    }
    public function ValidateAndInsertUser(Request $request, Response $response):response
    {
        //primero validar si el usuario existe
        //luego validar la info del usuario
        // luego validar la info de la anamnesis
        //insetar ambos
        //retornar mensaje acorde
        $body = $request->getParsedBody();
        $this->validator = $this->validator->withData($body);
        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }
        $id = $this->usuariosRepository->InserNewAnamnesis($body);
        if (empty($id)){
            $body = json_encode([
                'message' => 'Error Insertando Anamnesis',
                'id' => $id
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(500);
        }
        $body = json_encode($body);
        $response->getBody()->write($body);

        $body = json_encode([
            'message' => 'Empleado registrdo exitosamente',
            'id' => $id
        ]);
        return $response->withStatus(201);
    }
}
