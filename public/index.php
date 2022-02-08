<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RedBeanPHP\R as R;
use slimApp\controllers\PersonController;
use DI\Bridge\Slim\Bridge;

require_once "../vendor/autoload.php";

//REDBEAN
//Initialisation de la connexion à une base MySQL ou MariaDB
$dsn = "mysql:host=localhost;dbname=testcours;charset=utf8";
$user = "root";
$pass = "";
R::setup($dsn, $user, $pass);


$builder = new DI\ContainerBuilder();
$container = $builder->build();

$app = Bridge::create($container);

$app->get('/hello[/{name}]', function (Request $request, Response $response, array $args) {

    $name = $args["name"] ?? "inconnu";
    $response->getBody()->write("Hello $name");
    return $response;
});

$app->get('/person/insert[/{firstName}[/{lastName}]]', [PersonController::class, "insertOne"]);

$app->get('/person/all',[PersonController::class, "showAll"]);

$app->run();