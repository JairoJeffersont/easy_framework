<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

//EXAMPLE CONTROLLER
class UserController {

    //GET EXAMPLE
    public function index() {
        //USER DATA EXAMPLE
        $data = [
            'name' => 'John Smith',
            'age' => 19
        ];
        return Response::success('List of users', $data);
    }

    //POST EXAMPLE
    public function store() {
        $data = Request::input();
        return Response::success('User created.', $data);
    }
}
