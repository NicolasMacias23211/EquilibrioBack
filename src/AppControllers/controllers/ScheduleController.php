<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\sheduleRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ScheduleController
{
    public function __construct(private sheduleRepository $sheduleRepository)
    {
    }

    public function getScheduleByPerson(Request $request, Response $response, string $document): Response
    {
        if (empty($document)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'El parámetro "document" es obligatorio.']));
            return $response->withStatus(400);
        }

        $shedule = $this->sheduleRepository->getSheduleByMemberDocument($document);
        if (empty($shedule)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'No se encontraron horarios para el documento proporcionado.']));
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($shedule));
        return $response->withStatus(200);
    }
}


