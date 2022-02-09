<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use RedBeanPHP\R as R;

class PersonController {

    public function showAll(Response $response){
        $listPerson = R::findAll("persons");
        $response->getBody()->write(json_encode($listPerson));
        return $response; 
    }

    public function showOne($id, Response $response){
        $response->getBody()->write($id);
        return $response; 
    }

    public function insertOne(Response $response, $firstName, $lastName){
    $newPerson = R::dispense("persons");
    $newPerson->firstName = $firstName;
    $newPerson->lastName = $lastName;
    $id = R::store($newPerson);
    $response->getBody()->write("Ajout de $newPerson->firstName $newPerson->lastName avec l'id: $id");

    return $response;
    }
}