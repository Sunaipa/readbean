<?php

namespace slimApp\middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\RequestInterface;

class ApiKeyMiddleware  {
    protected $key1;

    public function __construct($key1)
    {
        $this->key1 = $key1;
    }

    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler)  {

        $key = filter_input(INPUT_GET, "key");
        if($key != $this->key1) {
            throw new \Exception("Clef non valide");
        }
        $response = $handler->handle($request);
        return $response;
    }
}