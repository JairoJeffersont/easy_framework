<?php

use App\Core\Router;

// Create a new instance of the Router class.
$router = new Router();

// USER ROUTES EXAMPLES
//
// These routes handle HTTP requests related to user actions.

// Register a GET route for '/users' to fetch all users.
// The route is associated with the 'list' method in the UserController class.
$router->get('/users', 'App\Controllers\UserController@list');

// Register a GET route for '/users/id' to fetch a user from id.
// The route is associated with the 'findById' method in the UserController class.
$router->get('/users/{id}', 'App\Controllers\UserController@findById');


// Register a POST route for '/users' to create a new user.
// The route is associated with the 'store' method in the UserController class.
$router->post('/users', 'App\Controllers\UserController@store');
