<?php


require __DIR__ . '/../vendor/autoload.php';

use App\Core\Request;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require __DIR__ . '/../routes/web.php';

$router->dispatch(Request::capture());
