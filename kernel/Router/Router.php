<?php

namespace App\Kernel\Router;

use App\Kernel\Http\RequestInterface;

class Router implements RouterInterface
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct()
    {
        $this->initRoutes();
    }

    public function dispatch(string $uri, string $method): void
    {
        $route = $this->findRoute($uri, $method);
        
        if (!file_exists(APP_PATH . '/public' . $uri)) {
            if (!$route) {
                $this->notFound();
            }
        }


        $parameters = $route->getParameters();

        if ($route->hasMiddlewares()) {
            foreach ($route->getMiddlewares() as $middleware) {
                $middleware = new $middleware();

                $middleware->handle();
            }
        }

        if (is_array($route->getAction())) {
            [$controller, $action] = $route->getAction();

            $controller = new $controller();

            call_user_func_array([$controller, $action], $parameters);
        } else {
            call_user_func_array($route->getAction(), $parameters);
        }
    }


    private function notFound(): void
    {
        echo 'Route not found';
        exit();
    }

    private function findRoute(string $uri, string $method)
    {
        foreach ($this->routes[$method] as $path => $route) {
            $pattern = "#^" . preg_replace('#\{[\w]+\}#', '([\w]+)', $path) . "$#D";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $route->setParameters($matches);
                return $route;
            }
        }
        return false;
    }


    private function initRoutes(): void
    {
        $routes = $this->getRoute();

        foreach ($routes as $route) {
            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }

    /**
     * @return Route
     */
    private function getRoute()
    {
        return require_once APP_PATH . '/app/routes.php';
    }
}