<?php

namespace App\Kernel;

use App\Kernel\Http\Request;
use App\Kernel\Http\RequestInterface;
use App\Kernel\Router\Router;
use App\Kernel\Router\RouterInterface;

class App
{
    private RequestInterface $request;
    private RouterInterface $router;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->router = new Router();
    }


    public function run(): void
    {
        $this->router->dispatch(
            $this->request->uri(),
            $this->request->method()
        );
    }
}
