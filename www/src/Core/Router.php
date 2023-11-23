<?php
namespace App\Core;
class Router
{
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch()
    {
        // Get the requested URI
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Basic routing based on the URI
        foreach ($this->routes as $route => $handler) {
            $pattern = '#^' . $route . '$#';

            if (preg_match($pattern, $requestUri, $matches)) {
                list($controllerName, $action) = $handler;
                $controller = new $controllerName();

                // Pass matched parameters to the action
                $controller->$action(...array_slice($matches, 1));
                return; // Stop processing further routes after a match
            }
        }

        // No matching route found
        $this->notFoundResponse();
    }

    private function notFoundResponse()
    {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Route not found']);
    }
}
