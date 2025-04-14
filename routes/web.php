<?php

use App\Core\Router;

$router = new Router();

//USER ROUTES EXAMPLES
$router->get('/users', 'App\Controllers\UserController@index');
$router->post('/users', 'App\Controllers\UserController@store');