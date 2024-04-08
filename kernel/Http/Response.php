<?php

namespace App\Kernel\Http;

class Response implements ResponseInterface
{
    public function json(array $data, int $http_code): void
    {
        header('Content-Type: application/json');
        http_response_code($http_code);
        echo json_encode($data);
        exit;
    }

    public function ajaxError($msg = [], $http_code = 400): void
    {

        $resp = [
            'status' => false,
            'error' => [
                'message' => $msg
            ]
        ];
        $this->json($resp, $http_code);
    }

    public function ajaxSuccess($response = [], $http_code = 200): void
    {
        $resp = [
            'status' => true,
            'response' => $response
        ];

        $this->json($resp, $http_code);
    }

    public function view(string $name): void
    {
        $viewPath = APP_PATH . "/views/$name.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View $name not found");
        }

        include_once $viewPath;
    }

}