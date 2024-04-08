<?php

namespace App\Kernel\Http;

use App\Kernel\Validator\ValidatorInterface;

class Request implements RequestInterface
{
    private ValidatorInterface $validator;

    public function __construct(
        public array $get,
        public array $post,
        public array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_SERVER);
    }

    public function uri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->get;
        }
        if (!isset($this->get[$key])) {
            return $default;
        }
        return is_numeric($this->get[$key]) ? (int)$this->get[$key] : $this->get[$key];
    }

    public function post($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->post;
        }
        if (!isset($this->post[$key])) {
            return $default;
        }
        return is_numeric($this->post[$key]) ? (int)$this->post[$key] : $this->post[$key];
    }

    public function input($key, $default = null)
    {
        return $this->post($key) ?? $this->get($key) ?? $this->json($key) ?? $default;
    }

    public function raw()
    {
        return file_get_contents('php://input');
    }

    public function json($key = null)
    {
        $raw = json_decode($this->raw());

        if (is_null($key)) {
            return $raw;
        }

        if (!isset($raw->{$key})) {
            return null;
        }
        return $raw->{$key};
    }

    public function header($key, $default = null)
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));

        return $this->server[$key] ?? $default;
    }


    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function validate(array $rules): bool
    {
        $data = [];
        foreach ($rules as $field => $rule) {
            $data[$field] = $this->input($field);
        }
        return $this->validator->validate($data, $rules);
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }
}