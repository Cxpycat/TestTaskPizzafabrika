<?php

namespace App\Kernel\Controller;

use App\Kernel\Http\Request;
use App\Kernel\Http\RequestInterface;
use App\Kernel\Http\ResponseInterface;
use App\Kernel\Validator\Validator;
use App\Kernel\Http\Response;

abstract class Controller
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->request->setValidator(new Validator());
        $this->response = new Response();
    }
}