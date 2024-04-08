<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;

class Documentation extends Controller
{
    public function index()
    {
        $this->response->view('swagger');
    }

}