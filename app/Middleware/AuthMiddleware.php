<?php

namespace App\Middleware;

use App\Kernel\Config\Config;
use App\Kernel\Middleware\AbstractMiddleware;

class AuthMiddleware extends AbstractMiddleware
{

    public function handle(): void
    {
        if ($this->request->header('X-Auth-Key') !== Config::get('auth.key')) {
            $this->response->ajaxError('Unauthorized', 401);
        }
    }
}