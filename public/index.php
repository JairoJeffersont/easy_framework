<?php

// Autoload all dependencies using Composer's autoload file.
require __DIR__ . '/../vendor/autoload.php';

// Import necessary classes.
use App\Core\Request;
use Dotenv\Dotenv;

// Create a new instance of Dotenv to load environment variables from the '.env' file.
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Include the routes file that contains route definitions.
require __DIR__ . '/../routes/web.php';

// Dispatch the current HTTP request to the appropriate route.
// The Request::capture() method captures the current request and passes it to the router for dispatching.
$router->dispatch(Request::capture());
