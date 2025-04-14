<?php

use App\Core\Router;

// Create a new instance of the Router class.
$router = new Router();

// USER ROUTES EXAMPLES
//
// These routes handle HTTP requests related to user actions.

// Register a GET route for '/users' to fetch all users.
// The route is associated with the 'index' method in the UserController class.
$router->get('/users', 'App\Controllers\UserController@index');

// Register a POST route for '/users' to create a new user.
// The route is associated with the 'store' method in the UserController class.
$router->post('/users', 'App\Controllers\UserController@store');
