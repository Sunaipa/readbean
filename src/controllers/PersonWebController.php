<?php
namespace slimApp\controllers;

use RedBeanPHP\R as R;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

class PersonWebController  extends AbstractWebController {

    public function showAll(Response $response) {
        $listPerson = R::findAll("persons");
        return $this->render($response,"show-persons.twig",["persons" => $listPerson]);
    }

    public function showOne(Response $response, $id) {
        $person = $this->getOnePersonFromId($id);
        return $this->render($response,"show-person.twig",["person" => $person]);
    }

    public function showForm(Response $response, $id = null) {
        $person = $this->getOnePersonFromId($id);
        return $this->render($response,"show-form.twig",["person" => $person]);          
    }

    public function processForm(Response $response, ServerRequestInterface $request) {

        $data = $request->getParsedBody();

        //if id present alors faire une modif sinon procéder en dessous

        $address = R::dispense("address");
        $address->import($data["address"]);
        $id = R::store($address);

        if(empty($data["contact"]["id"])){
            $person  = R::dispense("person");
        } else {
            $person = R::load("person", $data["contact"]["id"]);
        }


        $person = R::dispense("persons");
        $person->import($data["contact"]);
        $person->address = $address;//creation clef etrangere
        $id = R::store($person);

        return $response->withStatus(302)->withHeader("location","/person/$id");

        // $newPerson = R::dispense("persons");    
        // $newPerson->firstName = filter_input(INPUT_POST, "firstName");
        // $newPerson->lastName = filter_input(INPUT_POST, "lastName");
        // $id = R::store($newPerson);
        // return $response->withStatus(302)->withHeader("location","/person/$id");
        // // return $this->render($response,"show-person.twig",["id" => $id]);
    }

    public function getOnePersonFromId($id) {
        if(! empty($id)){
            $person = R::load("person", $id);
        
            if(! empty($person->address_id)){
                $address = R::load("address", $person->address_id);
                $person->address = $address;
            } 
        
        } else {
            $person = null;
        }

        return $person;
    }

}