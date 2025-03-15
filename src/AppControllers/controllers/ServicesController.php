<?php
declare(strict_types=1);
namespace AppControllers\controllers;

use AppRepository\Repositories\ServicesRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ServicesController
{
    public function __construct(private ServicesRepository $ServiciosRepository)
    {
    }
    
    public function getAllServices(Request $request, Response $response):response
    {
        $data = $this->ServiciosRepository->getAllServices();
        $doby = json_encode($data);
        $response->getBody()->write($doby);
        return $response;
    }



}