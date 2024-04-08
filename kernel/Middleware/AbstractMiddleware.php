<?php

namespace App\Kernel\Middleware;

use App\Kernel\Http\Request;
use App\Kernel\Http\RequestInterface;
use App\Kernel\Http\Response;
use App\Kernel\Http\ResponseInterface;

abstract class AbstractMiddleware
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
    }

    abstract public function handle(): void;
}