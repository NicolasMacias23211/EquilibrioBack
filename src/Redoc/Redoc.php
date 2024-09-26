<?php
declare(strict_types=1);
namespace Redoc;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Redoc
{

    public function __construct()
    {
    }

    public function getDoc(Request $request, Response $response):response
    {
            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Documentación de la API</title>
            </head>
            <body>
              <redoc spec-url="openapi.yaml"
                   hide-host="false" 
                   hide-loading="true" 
                   required-props-first="true"
                   default-expand-operations-under="true" 
                   scroll-xy="true"
                   theme=\'{"colors":{"primary":{"main":"#FF5733"}}}\'
                   logo=\'{"url": "https://media.istockphoto.com/id/636379014/es/foto/manos-la-formaci%C3%B3n-de-una-forma-de-coraz%C3%B3n-con-silueta-al-atardecer.jpg?s=612x612&w=0&k=20&c=R2BE-RgICBnTUjmxB8K9U0wTkNoCKZRi-Jjge8o_OgE="}\'>
              </redoc>
              
              <script src="https://cdn.redoc.ly/redoc/latest/bundles/redoc.standalone.js"></script>
            </body>
            </html>
        ';
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }

}