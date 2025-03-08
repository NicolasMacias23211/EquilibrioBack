<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\membersRepository;
use AppRepository\Repositories\RolesRepository;
use AppRepository\Repositories\fieldOfStudyRepository;
use AppRepository\Repositories\MembersFieldsOfStudyRepository;
use AppRepository\Repositories\daysRepository;
use AppRepository\Repositories\membersByServicesRepository;
use AppRepository\Repositories\sheduleRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class membersController
{
    public function __construct(
    private membersRepository $membersRepository,
    private Validator $validator,
    private RolesRepository $rolesRepository,
    private fieldOfStudyRepository $fieldOfStudyRepository,
    private MembersFieldsOfStudyRepository $membersFieldsOfStudyRepository,
    private daysRepository $daysRepository,
    private sheduleRepository $sheduleRepository,
    private membersByServicesRepository $membersByServicesRepository
    )
    {
    }

    public function allProfessionals(Request $request, Response $response):response
    {
        $data = $this->membersRepository->GetAllprofessionals();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }

    /**
     * Servicio para crear un profesional
     *
     * @param Request $request
     * @param Response $response
     * @return response
     */
    public function CreateProfessional(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $validator = $this->getCreateProfessional($body);
        if (! $this->validator->validate()){
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response ->withStatus(422);
        }
        $hashedPassword = password_hash($body['password'], PASSWORD_DEFAULT);
        $body['password'] = $hashedPassword;
        $roles = $this->rolesRepository->getAllRoles();
        $roleNames = array_column(json_decode($roles), 'roleName');

        // Validar si el roleName enviado está en la lista de roles disponibles
        if (!in_array($body['roleName'], $roleNames)) {
            $response->getBody()->write(json_encode(['success' => false, 'Message' => 'El rol especificado no es válido']));
            return $response->withStatus(422);
        }
        // Obtener el roleID que concuerda con el roleName enviado en la petición
        foreach (json_decode($roles, true) as $role) {
            if ($role['roleName'] === $body['roleName']) {
            $roleID = $role['roleID'];
            break;
            }
        }
        $body['roles_roleID'] = $roleID;
        $body['userType'] = 'professional';
        $body['memberStatus'] = 'E';
        $id = $this->membersRepository->createMembers($body);
        //se valida que el campo de estudio exista
        $IdFieldStudy = $this->fieldOfStudyRepository->getFieldOfStudyByName($body['nameFieldStudy']);
        if (empty($IdFieldStudy)) {
            $response->getBody()->write(json_encode(['success' => false, 'Message' => 'El campo de estudio especificado no es válido']));
            return $response->withStatus(422);
        }
        //se asigna el campo de estudio al profesional
        $this->membersFieldsOfStudyRepository->createMembersFieldsOfStudy([
            'members_document' => $id,
            'fieldOfStudy_fieldOfStudyID' => $IdFieldStudy[0]['fieldOfStudyID']
        ]);    

        foreach ($body['agenda'] as $agenda) {
            // se obtiene el dia especificado
            $day = $this->daysRepository->getDayByName($agenda['dayName']);
            if (empty($day)) {
                $response->getBody()->write(json_encode(['success' => false, 'Message' => 'El día especificado no es válido']));
                return $response->withStatus(422);
            }
            $this->sheduleRepository->createShedule($agenda, $day[0]['dayID']);
        }


        // se asignan los servicios al profesional
        foreach ($body['services'] as $service) {
            $this->membersByServicesRepository->createMembersByServices([
                'members_document' => $id,
                'serviceID' => $service['serviceID'],
                'servicePackagesID' => $service['servicePackagesID']
            ]);
        }
        
        $body = json_encode([
            'message' => 'Empleado registrdo exitosamente',
            'id' => $id
        ]);
        $body = json_encode($body);
        $response->getBody()->write($body);
        return $response->withStatus(201);
    }



    /**
     * Este valdiador se encarga de validar los campos requeridos para la creación de un profesional
     * La idea de esto es crear los necesarios para cada end-point 
     *
     * @param array $data campo que tiene todo la data como la envia el front 
     * @return Validator objeto propio de la libreria se valida de siempre de la siguiente forma: if (! $this->validator->validate()){return $response->withStatus(422);}
     */
    private function getCreateProfessional(array $data): Validator
    {
        $validator = new Validator($data);
        $validator->mapFieldsRules([
            'nombre' => ['required'],
            'primerApellido' => ['required'],
            'segundoApellido' => ['required'],
            'fechaNacimiento' => ['required'],
            'documento' => ['required'],
            'userType' => ['required'],
            'roleName' => ['required'],
            'sexo' => ['required'],
            'correoElectronico' => ['required'],
            'telefono' => ['required'],
            'direccion' => ['required'],
            'ocupacion' => ['required'],
            'rh' => ['required'],
            'username' => ['required'],
            'contrasena' => ['required'],
        ]);
        return $validator;
    }


}