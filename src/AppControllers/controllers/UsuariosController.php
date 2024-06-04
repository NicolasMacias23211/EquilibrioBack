<?php

namespace AppControllers\controllers;

use AppRepository\Repositories\UsuariosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class UsuariosController
{
    public function __construct(private UsuariosRepository $usuariosRepository,private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'antecendetesQuirurgicos' => ['required'],
            'antecendetesFarmaceuticos' => ['required'],
            'antecendetesToxicos' => ['required'],
            'antecendetesFamiliares' => ['required'],
            'bebe' => ['required'],
            'fuma' => ['required'],
            'ejercisio' => ['required'],
            'nombre' => ['required'],
            'primerApellido' => ['required'],
            'segundoApellido' => ['required'],
            'fechaNacimiento' => ['required'],
            'sexo' => ['required'],
            'correoElectronico' => ['required'],
            'telefono' => ['required'],
            'direccion' => ['required'],
            'ocupacion' => ['required'],
            'rh' => ['required'],
            'username' => ['required'],
            'contrasena' => ['required'],
        ]);
    }
    public function ValidateAndInsertUser(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $this->validator = $this->validator->withData($body);
        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }
        if ($this->usuariosRepository->UsernameExists($body['username'])) {
            $body = json_encode([
                'message' => 'El nombre de usuario ya esta tomado'
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(500);
        }
        if ($this->usuariosRepository->GetUserByDocument($body['documento'])) {
            $body = json_encode([
                'message' => 'El usuario ya existe'
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(500);
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
        $body['anamnesisId'] = $id;
        $IdUser = $this->usuariosRepository->InsertNewUser($body);
        if (empty($IdUser)){
            $body = json_encode([
                'message' => 'Error Insertando el usuario',
                'id' => $IdUser
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(500);
        }
        $body['usuariosId'] = $IdUser;
        $credencialesID = $this->usuariosRepository->InsertNewCredential($body);
        if (!empty($credencialesID)){
            $body = json_encode([
                'message' => 'Insertado Correctamente',
                'insert' => true
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(200);
        }else{
            $body = json_encode([
                'message' => 'Error Insertando las credenciales'
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withStatus(500);
        }
    }
}
