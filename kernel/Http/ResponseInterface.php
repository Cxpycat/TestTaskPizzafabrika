<?php

namespace App\Kernel\Http;

interface ResponseInterface
{
    public function json(array $data, int $http_code): void;

    public function ajaxError($msg = '', $http_code = 400): void;

    public function ajaxSuccess($response = [], $http_code = 200): void;

    public function view(string $name): void;
}