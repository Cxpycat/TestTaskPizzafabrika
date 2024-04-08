<?php

namespace App\Kernel\Router;

class Route implements RouteInterface
{
    private array $parameters = [];

    public function __construct(
        private string $uri,
        private string $method,
        private        $action,
        private array  $middlewares = []
    )
    {
    }


    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public static function get(string $uri, $action, array $middlewares = []): static
    {
        return new static($uri, 'GET', $action, $middlewares);
    }

    public static function post(string $uri, $action, array $middlewares = []): static
    {
        return new static($uri, 'POST', $action, $middlewares);
    }


    public function getAction()
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function hasMiddlewares(): bool
    {
        return !empty($this->middlewares);
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}