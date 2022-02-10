<?php
namespace slimApp\controllers;

use Exception;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController extends AbstractWebController{

    public function hello(Response $response, $name = "Inconnue"){

        // throw new Exception("ERREUR");
        // $response->getBody()->write("Hello $name ");
        // return $response;   

        return $this->render(
                $response,
                "hello.twig",
                [
                    "name" => $name,
                    "skills" => ["java","PHP", "Python"],
                    "showSkills" => true
                ]
        );
    }
}