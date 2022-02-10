<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use RedBeanPHP\R as R;

class PersonWebController  extends AbstractWebController {

    public function showAll(Response $response) {
        $listPerson = R::findAll("persons");
        return $this->render($response,"show-persons.twig",["persons" => $listPerson]);
    }

    public function showOne(Response $response, $id) {
        $person = R::load( "persons", $id);
        return $this->render($response,"show-person.twig",["person" => $person]);
    }

    public function showForm(Response $response) {
        return $this->render($response,"show-form.twig",[]);
    }

    public function processForm(Response $response) {
        $newPerson = R::dispense("persons");    
        $newPerson->firstName = filter_input(INPUT_POST, "firstName");
        $newPerson->lastName = filter_input(INPUT_POST, "lastName");
        $id = R::store($newPerson);
        return $response->withStatus(302)->withHeader("location","/person/$id");
        // return $this->render($response,"show-person.twig",["id" => $id]);
    }

}