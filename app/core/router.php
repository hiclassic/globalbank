<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($requestMethod, $requestPath)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $requestPath) {
                $handler = $route['handler'];
                return $handler();
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route Not Found']);
    }
}
