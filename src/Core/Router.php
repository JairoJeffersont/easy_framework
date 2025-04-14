<?php

namespace App\Core;

use App\Core\Response;



class Router {
    protected array $routes = [];

    public function get(string $uri, string $action) {
        $this->addRoute('GET', $uri, $action);
    }
    public function post(string $uri, string $action) {
        $this->addRoute('POST', $uri, $action);
    }
    public function put(string $uri, string $action) {
        $this->addRoute('PUT', $uri, $action);
    }
    public function delete(string $uri, string $action) {
        $this->addRoute('DELETE', $uri, $action);
    }

    protected function addRoute(string $method, string $uri, string $action) {
        $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $uri);
        $this->routes[$method][] = [
            'pattern' => "#^$pattern$#",
            'action'  => $action,
            'uri'     => $uri
        ];
    }

    public function dispatch(\App\Core\Request $request) {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // remove o match completo

                [$class, $methodName] = explode('@', $route['action']);

                if (!class_exists($class) || !method_exists($class, $methodName)) {
                    return Response::error('Route not found.', 404, [], 'not_found');
                    return;
                }

                $controller = new $class();
                $response = $controller->$methodName(...$matches);

                echo is_array($response) ? json_encode($response) : $response;
                return;
            }
        }

        return Response::error('Route not found.', 404, [], 'not_found');

    }
}
