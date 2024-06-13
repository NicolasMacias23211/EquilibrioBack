<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\UsersRepository;
use AppRepository\Repositories\UsuariosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;


class UsersController
{

    public function __construct(private UsersRepository $usersRepository,private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'nombre' => ['required'],
            'apellido' => ['required'],
            'Correo' => ['required'],
            'Documento' => ['required']
            //para integers  'numero' => ['required','integer',['min',1]]
        ]);
    }


}