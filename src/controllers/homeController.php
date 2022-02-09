<?php
namespace slimApp\controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController {

    public function hello(Response $response, $name = "Inconnue"){

        throw new Exception("ERREUR");

        $response->getBody()->write("Hello $name ");
        return $response;   
    }
}