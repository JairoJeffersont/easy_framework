<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

//EXAMPLE CONTROLLER
class HomeController {
    public function index() {
        return Response::success('Working API');
    }

    public function teste() {
        $data = Request::input();
        return Response::success('Working API', $data);
    }
}
