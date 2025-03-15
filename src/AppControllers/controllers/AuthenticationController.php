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
        $usuario = $body['username'];
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

        if ($password == null) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Usuario no encontrado']));
            return $response->withStatus(404);
        }
        if (password_verify($contraseña, $password)) {
            $response->getBody()->write(json_encode(['success' => true, 'message' => 'Autenticación exitosa']));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Contraseña incorrecta']));
            return $response->withStatus(401);
        }
    }

}