<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RedBeanPHP\R as R;

class PersonController {

    public function showAll(Request $request, Response $response, array $args){
        $listPerson = R::findAll("persons");
        $response->getBody()->write(json_encode($listPerson));
    
        return $response; 
    }

    public function insertOne(Request $request, Response $response, array $args){
    $newPerson = R::dispense("persons");
    if (count($args) > 1) {
        $newPerson->firstName = $args["firstName"];
        $newPerson->lastName = $args["lastName"];

        $id = R::store($newPerson);

        $response->getBody()->write("Ajout de $newPerson->firstName $newPerson->lastName avec l'id: $id");
    } else {
        $response->getBody()->write("Argument manquant");
    }
    return $response;
    }
}