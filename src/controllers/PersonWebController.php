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

        //recup data du formulaire
        $data = $request->getParsedBody();

        $address = R::dispense("address");
        $address->import($data["address"]);
        R::store($address);

        //Check si on est dans une modification ou un ajout de personne
        if(empty($data["contact"]["id"])){
            $person  = R::dispense("persons");
        } else {
            $person = R::load("persons", $data["contact"]["id"]);
            $person->ownPhones = [];
        }

        //S'il y a des telephone, creer l'entrer dans la table
        if(isset($data["phones"])){
            $phoneNumbers =$data["phones"]["numbers"];
            for($i = 0; $i < count($phoneNumbers); $i++) {
                if(!empty(trim($phoneNumbers[$i]))){
                    $phone = R::dispense("phones");
                    $phone->number = $phoneNumbers[$i];
                    $person->ownPhones[] = $phone;
                }
            }   
        }

        //$person = R::dispense("persons");
        $person->import($data["contact"]);
        $person->address = $address;//creation clef etrangere
        R::store($person);

        R::exec("DELETE FROM phones WHERE persons_id IS NULL");

         return $response->withStatus(302)->withHeader("location","/person");
    }

    public function getOnePersonFromId($id) {
        if(! empty($id)){
            $person = R::load("persons", $id);
            if(! empty($person->address_id)){
                $address = R::load("address", $person->address_id);
                $person->address = $address;
            } 

            /*
            $phones = R::find("phones", $id);
            if(! empty($phones)){
                $person->ownPhones =  $phones;
            }
            */
            
            $phones = [];
            foreach($person->ownPhones as $phone){
                $phones[] = $phone->number;
            }
            $person->ownPhones = $phones;

            //var_dump($person->ownPhones);
            //exit;
            
        } else {
            $person = null;
        }
        return $person;
    }

}