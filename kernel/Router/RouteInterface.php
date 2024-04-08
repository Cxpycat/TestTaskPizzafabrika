<?php

namespace App\Kernel\Router;

interface RouteInterface
{
    public function setParameters(array $parameters): void;

    public function getParameters(): array;

    public static function get(string $uri, $action, array $middlewares = []): static;

    public static function post(string $uri, $action, array $middlewares = []): static;

    public function getAction();

    public function getMethod(): string;

    public function getUri(): string;

    public function hasMiddlewares(): bool;

    public function getMiddlewares(): array;
}