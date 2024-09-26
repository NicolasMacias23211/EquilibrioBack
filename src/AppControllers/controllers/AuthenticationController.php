<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\AuthenticationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthenticationController
{
    public function __construct(private AuthenticationRepository $AutenticacionRepository){}

    public function validarCredenciales(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $usuario = $body['email'];
        $contraseña = $body['contraseña'];
        if ($contraseña == null || empty($contraseña)){
            $response->getBody()->write(json_encode("Error no llego la contraseña"));
            return $response ->withStatus(422);
        }
        if ($usuario == null || empty($usuario)){
            $response->getBody()->write(json_encode("Error no llego el usuario"));
            return $response ->withStatus(422);
        }
        $data = $this->AutenticacionRepository->GetAutentication($usuario,$contraseña);
        if ($data != null && !empty($data)){
            $body = json_encode([
                'message' => 'Usuario y contraseña correctos',
                'id' => $data
            ]);
            $body = json_encode($body);
            $response->getBody()->write($body);
            return $response->withHeader('Content-Type','application/json');
        }else{
            $response->getBody()->write(json_encode(['message' => 'Error no se encontro un usuario registrado con las credenciales',
            'id' => 0]));
            return $response ->withStatus(422);
        }
    }

}