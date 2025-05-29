<?php
declare(strict_types=1);

namespace AppControllers\controllers;

use src\models\AppointmentStatus;
use AppRepository\Repositories\membersRepository;
use AppRepository\Repositories\sheduleRepository;
use AppRepository\Repositories\ServicesRepository;
use AppRepository\Repositories\AppointmentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AppointmentController{

    public function __construct(
        private membersRepository $membersRepository,
        private sheduleRepository $sheduleRepository,
        private ServicesRepository $servicesRepository,
        private AppointmentRepository $appointmentRepository
    ){
        
    }

    public function getAllAppointments(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $customerId = isset($params['customerID']) ? (int)$params['customerID'] : null;
        $data = $this->appointmentRepository->getAllAppointments($customerId);
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }

    public function createAppointment(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        if (empty($body['addres'])) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Address is required']));
            return $response->withStatus(404);
        }
        $member = $this->membersRepository->getMemberByDocument($body['UserDocumentId']);
        if (empty($member)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Member not found']));
            return $response->withStatus(404);
        }
        $appointment = $this->sheduleRepository->getSheduleByPrimaryKey($body['sheduleId']);
        if (empty($appointment)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Appointment not found']));
            return $response->withStatus(404);
        }
        $service= $this->servicesRepository->getServiceById($body['ServiceId']);
        if (empty($service)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Service not found']));
            return $response->withStatus(404);
        }
        if (empty($body['appointmentStatus'])) {
            $body['appointmentStatus'] = 'nueva';
        }
        $result = $this->appointmentRepository->createAppointment($body);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus(200);
    }

    public function cancelAppointmentById(Request $request, Response $response, string $appointmentId): Response
    {
        $appointmentId = (int)$appointmentId;
        if ($appointmentId <= 0) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid appointment ID']));
            return $response->withStatus(400);
        }
        $result = $this->appointmentRepository->cancelAppointmentById($appointmentId);
        if (!$result) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Appointment not found or already cancelled']));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode(['success' => true, 'message' => 'Appointment cancelled successfully']));
        return $response->withStatus(200);
    }

}