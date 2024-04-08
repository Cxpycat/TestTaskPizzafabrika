<?php

namespace App\Kernel\Http;

use App\Kernel\Validator\ValidatorInterface;

interface RequestInterface
{
    public function uri(): string;

    public function method(): string;

    public function get($key = null, $default = null);

    public function post($key = null, $default = null);

    public function input($key, $default = null);

    public function raw();

    public function json($key = null);

    public function header($key, $default = null);

    public function setValidator(ValidatorInterface $validator): void;

    public function validate(array $rules): bool;

    public function errors(): array;
}