<?php
declare(strict_types=1);

namespace AppControllers\controllers;

use AppRepository\Repositories\membersRepository;
use AppRepository\Repositories\RolesRepository;
use AppRepository\Repositories\fieldOfStudyRepository;
use AppRepository\Repositories\MembersFieldsOfStudyRepository;
use AppRepository\Repositories\daysRepository;
use AppRepository\Repositories\membersByServicesRepository;
use AppRepository\Repositories\anamnesisRepository;
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
    private membersByServicesRepository $membersByServicesRepository,
    private anamnesisRepository $anamnesisRepository
    )
    {
    }

    public function allProfessionals(Request $request, Response $response):response
    {
        $params = $request->getQueryParams();
        $serviceID = isset($params['serviceID']) ? (int)$params['serviceID'] : null;
        $data = $this->membersRepository->GetAllprofessionals($serviceID);
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
        if (!$validator->validate()){
            $response->getBody()->write(json_encode($validator->errors()));
            return $response ->withStatus(422);
        }
        //se valida que el campo de estudio exista
        $IdFieldStudy = $this->fieldOfStudyRepository->getFieldOfStudyByName($body['nameFieldStudy']);
        if (empty($IdFieldStudy)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'El campo de estudio especificado no es válido']));
            return $response->withStatus(422);
        }

        $hashedPassword = password_hash($body['password'], PASSWORD_DEFAULT);
        $body['password'] = $hashedPassword;
        $rol = "";
        if ($body['roleName'] != null) {
           $rol = $this->rolesRepository->getRoleByName($body['roleName']);
        } else {
            $rol = $this->rolesRepository->getRoleByName('Profesional');
        }

        if (!$rol['success']) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'El rol especificado no es válido']));
            return $response->withStatus(422);
        }

        $body['roles_roleID'] = $rol['data']['roleID'];
        $body['userType'] = 'professional';
        $body['memberStatus'] = 'E';
        $Ismemberinserted = $this->membersRepository->createMembers($body);

        if (!$Ismemberinserted['success']) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => $Ismemberinserted['message']]));
            return $response->withStatus(500);
        }

        $id = $Ismemberinserted['id'];
        //se asigna el campo de estudio al profesional
        $this->membersFieldsOfStudyRepository->createMembersFieldsOfStudy([
            'members_document' => $id,
            'fieldOfStudy_fieldOfStudyID' => $IdFieldStudy[0]['fieldOfStudyID']
        ]);    

        foreach ($body['agenda'] as $agenda) {
            // se obtiene el dia especificado
            $day = $this->daysRepository->getDayByName($agenda['dayName']);
            if (empty($day)) {
                $response->getBody()->write(json_encode(['success' => false, 'message' => 'El día especificado no es válido']));
                return $response->withStatus(422);
            }
            $agenda['member_document'] = $id;
            $this->sheduleRepository->createShedule($agenda, $day[0]['dayID']);
        }


        // se asignan los servicios al profesional
        foreach ($body['services'] as $service) {
            $this->membersByServicesRepository->createMembersByServices([
                'members_document' => $id,
                'serviceID' => $service['serviceID'] ?? null,
                'servicePackagesID' => $service['servicePackagesID'] ?? null
            ]);
        }
        
        $body = json_encode([
            'message' => 'Empleado registrdo exitosamente',
            'id' => $id
        ]);

        $response->getBody()->write($body);
        return $response->withStatus(201);
    }

    public function createNewMember(Request $request, Response $response):response
    {
        $body = $request->getParsedBody();
        $validator = $this->getCreateMember($body);
        if (! $validator->validate()){
            $response->getBody()->write(json_encode($validator->errors()));
            return $response ->withStatus(422);
        }
        $hashedPassword = password_hash($body['password'], PASSWORD_DEFAULT);
        $body['password'] = $hashedPassword;
        $rol = "";
        if (array_key_exists('roleName', $body) && $body['roleName'] != null) {
           $rol = $this->rolesRepository->getRoleByName($body['roleName']);
        } else {
            $rol = $this->rolesRepository->getRoleByName('member');
        }

        if (!$rol['success']) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'El rol especificado no es válido']));
            return $response->withStatus(422);
        }

        $body['roles_roleID'] = $rol['data']['roleID'];
        $body['userType'] = 'member';
        $body['memberStatus'] = 'E';

        $anamnesis = $this->anamnesisRepository->createNewAnamnesis($body);
        if (!$anamnesis['success']) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => $anamnesis['message']]));
            return $response->withStatus(500);
        }

        $body['anamnesisID'] = $anamnesis['id'];

        $Ismemberinserted = $this->membersRepository->createMembers($body);

        if (!$Ismemberinserted['success']) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => $Ismemberinserted['message']]));
            return $response->withStatus(500);
        }

        $id = $Ismemberinserted['id'];
        $body = json_encode([
            'message' => 'mienbro registrdo exitosamente',
            'success' => true,
            'id' => $id
        ]);

        $response->getBody()->write($body);
        return $response->withStatus(201);

    }


    public function getMemberByDocument(Request $request, Response $response, string $document): response
    {
        $document = (int)$document;
        $member = $this->membersRepository->getMemberByDocument($document);
        $response->getBody()->write(json_encode($member));
        return $response;
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
            'userName' => ['required'],
            'password' => ['required'],
        ]);
        return $validator;
    }

    private function getCreateMember(array $data): Validator
    {
        $validator = new Validator($data);
        $validator->mapFieldsRules([
            'nombre' => ['required'],
            'primerApellido' => ['required'],
            'segundoApellido' => ['required'],
            'documento' => ['required'],
            'fechaNacimiento' => ['required', 'date'],
            'sexo' => ['required'],
            'correoElectronico' => ['required', 'email'],
            'telefono' => ['required', 'integer'],
            'direccion' => ['required'],
            'ocupacion' => ['required'],
            'rh' => ['required'],
            'antecendetesQuirurgicos' => ['required'],
            'antecendetesFarmaceuticos' => ['required'],
            'antecendetesToxicos' => ['required'],
            'fuma' => ['required', 'boolean'],
            'bebe' => ['required', 'boolean'],
            'ejercisio' => ['required', 'boolean'],
            'antecendetesFamiliares' => ['required'],
            'userName' => ['required'],
            'password' => ['required']
        ]);
        return $validator;
    }   


}