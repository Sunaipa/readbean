<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use RedBeanPHP\R as R;
use slimApp\controllers\PersonController;
use slimApp\controllers\HomeController;
use slimApp\middleware\TestMiddleware;
use slimApp\middleware\ApiKeyMiddleware;
use DI\Bridge\Slim\Bridge;
use Psr\Http\Message\RequestInterface;
use Middlewares\Whoops;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Views\Twig;
use slimApp\controllers\PersonWebController;

require_once "../vendor/autoload.php";

$dev = true;

//REDBEAN
//Initialisation de la connexion à une base MySQL ou MariaDB
$dsn = "mysql:host=localhost;dbname=testcours;charset=utf8";
$user = "root";
$pass = "";
R::setup($dsn, $user, $pass);


$builder = new DI\ContainerBuilder();
$container = $builder->build();

$container->set("twig", function(){
    return Twig::create("../views");
});

$middleware = function(RequestInterface $request, RequestHandler $handler){
    $response = $handler->handle($request);
    $response->getBody()->write("Hello from middleware ");
    return $response;
};

$middleware2 = function(RequestInterface $request, RequestHandler $handler){
    $response = $handler->handle($request);
    $response->getBody()->write("End of response ");
    return $response;
};

$app = Bridge::create($container);

//ajoute le middleWare à toute les routes
//$app->add($middleware);

if($dev) {
    $app->add(Whoops::class);
}

//Ici le middleware est attacher à cette route seulement
$app->get('/hello[/{name}]', [HomeController::class, "hello"])
->add($middleware)->add($middleware2)->add(new TestMiddleware()); 

$app->group("/api/person", function(RouteCollectorProxyInterface $group){
    $group->get('/insert[/{firstName}[/{lastName}]]', [PersonController::class, "insertOne"]);
    $group->get('/all',[PersonController::class, "showAll"]);
    $group->get("/{id}", [PersonController::class, "showOne"]);
})->add(new ApiKeyMiddleware(123));

$app->group("/person", function(RouteCollectorProxyInterface $group){
    $group->get('', [PersonWebController::class, "showAll"]);
    $group->get('/form[/{id:[0-9]+}]', [PersonWebController::class, "showForm"]);
    $group->post('/form', [PersonWebController::class, "processForm"]);
    $group->get('/{id:[0-9]+}',[PersonWebController::class, "showOne"]);
});
$app->run();