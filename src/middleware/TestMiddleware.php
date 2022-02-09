<?php

namespace slimApp\middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\RequestInterface;


class TestMiddleware {

    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler){

        $response  = $handler->handle($request);

        $response->getBody()->write("From a class middleware");
        return $response;
    }
}