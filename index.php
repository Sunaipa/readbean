<?php
require_once "vendor/autoload.php";

use RedBeanPHP\R as R;

//Initialisation de la connexion à une base MySQL ou MariaDB
$dsn = "mysql:host=localhost;dbname=testcours;charset=utf8";
$user = "root";
$pass = "";
R::setup($dsn, $user, $pass);

 //Définition des données
 $person = R::dispense("persons");
 $person->firstName = "toto";
 $person->lastName =  "Tolkien";

// $bookData = [
//     ["Les chants de Maldoror", "Lautréamont", 15.8, "Poésie", "Actes Sud"],
//     ["Une saison en enfer", "Arthur Rimbaud", 11.5, "Poésie", "Gallimard"],
//     ["Alcool", "Guillaume Apollinaire", 8.2, "Poésie", "Actes Sud"],
//     ["Les chants de Maldoror", "Lautréamont", 15.8, "Poésie", "PUF"],
//     ["Discours de la méthode", "René Descartes", 12.8, "Philosophie", "Hachette"],
//     ["La République", "Platon", 11.8, "Philosophie", "Gallimard"],
//     ["Pensées", "Blaise Pascal", 9.8, "Philosophie", "Hachette"],
//     ["Le Banquet", "Platon", 12.8, "Philosophie", "PUF"],
//    ];

// R::wipe( "persons" );

// foreach ($bookData as $data){
//     $book = R::dispense("books");
//     $book->title = $data[0];
//     $book->author = $data[1];
//     $book->price = $data[2];
//     $book->genre = $data[3];
//     $book->editor = $data[4];
//     R::store($book);
// }

//Persistance de l'entité
$id = R::store($person);