<?php

use App\Core\Router;

$router = new Router();

$router->get('/', 'App\Controllers\HomeController@index');
$router->post('/', 'App\Controllers\HomeController@teste');