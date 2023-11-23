<?php

define("BASE_DIR",__DIR__);
require_once 'vendor/autoload.php';
use App\Core\Router;

// Load routes configuration
$routes = require_once 'routes/api.php';

// Create an instance of the Router class
$router = new Router($routes);

// Dispatch the route and execute the corresponding controller action
$router->dispatch();
