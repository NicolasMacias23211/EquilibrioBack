<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\AuthenticationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;



class AuthenticationController
{
    public function __construct(private AuthenticationRepository $AutenticacionRepository){}

    public function validarCredenciales(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $validator = $this->validateLogin($body);
        if (!$validator->validate()){
            $response->getBody()->write(json_encode($validator->errors()));
            return $response ->withStatus(422);
        }
        $usuario = $body['userName'];
        $contraseña = $body['password'];
        if ($contraseña == null || empty($contraseña)){
            $response->getBody()->write(json_encode("Error no llego la contraseña"));
            return $response ->withStatus(422);
        }
        if ($usuario == null || empty($usuario)){
            $response->getBody()->write(json_encode("Error no llego el usuario"));
            return $response ->withStatus(422);
        }
        $password = $this->AutenticacionRepository->GetAutentication($usuario,$contraseña);

        if ($password === null || !isset($password['password'])) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Usuario no encontrado']));
            return $response->withStatus(404);
        }
        
        if (password_verify($contraseña, $password['password'])) {
            $response->getBody()->write(json_encode(['success' => true, 'message' => 'Autenticación exitosa' , 'id'=>$password['document']]));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Contraseña incorrecta']));
            return $response->withStatus(401);
        }
    }

    private function validateLogin(array $data): Validator
    {
        $validator = new Validator($data);
        $validator->mapFieldsRules([
            'userName' => ['required'],
            'password' => ['required'],
        ]);
        return $validator;
    }   



}