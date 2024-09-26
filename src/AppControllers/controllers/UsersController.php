<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;


class UsersController
{
    public function __construct(private UsersRepository $UsersRepository,private Validator $validator)
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
        $UserExist = $this->UsersRepository->UsernameExists($body['username']);
        $responseArray = json_decode($UserExist, true);
        if($responseArray['success']) {
            $response->getBody()->write($UserExist);
            return $response->withStatus(406);
        }
        $UserExist = $this->UsersRepository->GetUserByDocument($body['documento']);
        $responseArray = json_decode($UserExist, true);
        if($responseArray['success']) {
            $response->getBody()->write($UserExist);
            return $response->withStatus(406);
        }
        $id = $this->UsersRepository->InserNewAnamnesis($body);
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
        $IdUser = $this->UsersRepository->InsertNewUser($body);
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
        $credencialesID = $this->UsersRepository->InsertNewCredential($body);
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

    public function AllUsers(Request $request, Response $response):response
    {
        $data = $this->UsersRepository->GetAllUsers();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }

}