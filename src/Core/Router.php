<?php

namespace App\Core;

use App\Core\Response;

/**
 * Class Router
 *
 * This class is responsible for routing HTTP requests to the appropriate controller actions.
 * It handles the registration of routes and dispatches requests to the appropriate controller methods.
 */
class Router {
    /**
     * @var array An array of registered routes, indexed by HTTP method (GET, POST, etc.).
     */
    protected array $routes = [];

    /**
     * Register a GET route.
     *
     * This method adds a GET route to the router.
     *
     * @param string $uri The URI pattern to match.
     * @param string $action The controller action to be executed when the route is matched (e.g., 'Controller@method').
     * @return void
     */
    public function get(string $uri, string $action) {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route.
     *
     * This method adds a POST route to the router.
     *
     * @param string $uri The URI pattern to match.
     * @param string $action The controller action to be executed when the route is matched (e.g., 'Controller@method').
     * @return void
     */
    public function post(string $uri, string $action) {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a PUT route.
     *
     * This method adds a PUT route to the router.
     *
     * @param string $uri The URI pattern to match.
     * @param string $action The controller action to be executed when the route is matched (e.g., 'Controller@method').
     * @return void
     */
    public function put(string $uri, string $action) {
        $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a DELETE route.
     *
     * This method adds a DELETE route to the router.
     *
     * @param string $uri The URI pattern to match.
     * @param string $action The controller action to be executed when the route is matched (e.g., 'Controller@method').
     * @return void
     */
    public function delete(string $uri, string $action) {
        $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Add a route to the router.
     *
     * This method adds a route for the specified HTTP method, URI pattern, and associated controller action.
     *
     * @param string $method The HTTP method (e.g., GET, POST, etc.).
     * @param string $uri The URI pattern to match.
     * @param string $action The controller action to be executed when the route is matched (e.g., 'Controller@method').
     * @return void
     */
    protected function addRoute(string $method, string $uri, string $action) {
        // Convert URI pattern with placeholders into a regex pattern
        $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $uri);
        $this->routes[$method][] = [
            'pattern' => "#^$pattern$#",  // Store regex pattern for matching
            'action'  => $action,         // Store associated controller action
            'uri'     => $uri             // Store original URI pattern
        ];
    }

    /**
     * Dispatch the request to the appropriate controller action.
     *
     * This method checks the HTTP method and URI of the request, matches it to a registered route,
     * and executes the corresponding controller action. It returns an error response if no route is found.
     *
     * @param \App\Core\Request $request The current request object containing the HTTP method and URI.
     * @return void
     */
    public function dispatch(\App\Core\Request $request) {
        // Get the HTTP method and URI from the request
        $method = $request->method();
        $uri = $request->uri();

        // Iterate through the registered routes and check for a match
        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Remove the complete match from the matches array
                array_shift($matches);

                // Split the action into controller and method names
                [$class, $methodName] = explode('@', $route['action']);

                // Check if the controller class and method exist
                if (!class_exists($class) || !method_exists($class, $methodName)) {
                    return Response::error(404, [], 'not_found');
                }

                // Instantiate the controller and call the method with the matched parameters
                $controller = new $class();
                $response = $controller->$methodName(...$matches);

                // Output the response (encode as JSON if it's an array)
                echo is_array($response) ? json_encode($response) : $response;
                return;
            }
        }

        // Return an error if no matching route was found
        return Response::error(405, [], 'method_not_allowed');
    }
}
