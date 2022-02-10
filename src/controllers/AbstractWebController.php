<?php
namespace slimApp\controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;

Abstract class AbstractWebController { 

    protected Container $container;

    public function __construct(Container $c){
        $this->container = $c;
    }

    public function render(ResponseInterface $response, string $template, array $params =[]){
        return $this->container->get("twig")->render(
            $response,
            $template,
            $params
        );
    }

}
