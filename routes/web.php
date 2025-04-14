<?php

use App\Core\Router;

$router = new Router();

$router->get('/users', 'App\Controllers\UserController@index');

$router->post('/users', 'App\Controllers\UserController@store');