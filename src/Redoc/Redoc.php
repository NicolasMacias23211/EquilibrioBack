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

    public function getDoc(Request $request, Response $response): response
    {
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Documentación de la API</title>
          <style>
            body {
              background-color: #121212;
              color: #ffffff;
            }
              button[title="Download OpenAPI specification"] {
              display: none !important;
            }
            a[href$=".yaml"] {
              display: none !important;
            }
              .api-info p {
              display: none !important;
            }
          </style>
        </head>
        <body>
          <redoc spec-url="openapi.yaml"
               hide-host="false" 
               hide-loading="true" 
               required-props-first="true"
               default-expand-operations-under="true" 
               scroll-xy="true"
               disable-download-button="true" 
               theme=\'{
                  "colors": {
                    "primary": { "main": "#FF5733" },
                    "text": { "primary": "#b0a9a9", "secondary": "#f5f5f5" },
                    "background": { "main": "#121212", "secondary": "#1E1E1E" },
                    "http": {
                      "get": "#4caf50",
                      "post": "#1976d2",
                      "put": "#fbc02d",
                      "delete": "#d32f2f"
                    }
                  },
                  "typography": {
                    "fontSize": "16px",
                    "fontFamily": "Arial, sans-serif",
                    "headings": { "fontFamily": "Arial, sans-serif", "fontWeight": "bold", "color": "#ffffff" }
                  },
                  "sidebar": { "backgroundColor": "#1E1E1E", "textColor": "#ffffff" },
                  "rightPanel": { "backgroundColor": "#1E1E1E", "textColor": "#ffffff" },
                  "schema": {
                    "typeNameColor": "#FF5733",
                    "typeTitleColor": "#ffffff",
                    "requirementColor": "#fbc02d",
                    "labelsTextSize": "14px"
                  }
               }\'
               logo=\'{
                  "url": "https://res.cloudinary.com/dzcwmdees/image/upload/v1740691455/CataBlanca_lvxwkg.png"
               }\'>
          </redoc>
          
          <script src="https://cdn.redoc.ly/redoc/latest/bundles/redoc.standalone.js"></script>
        </body>
        </html>
        ';
        
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }
}
