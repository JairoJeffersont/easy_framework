<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\UserModel;
use PDOException;

//EXAMPLE CONTROLLER
class UserController {

    protected UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }


    //GET EXAMPLE
    public function list() {
        try {
            $users = $this->userModel->findAll();
            return Response::success('List of users', $users);
        } catch (PDOException $e) {
            return Response::error();
        }
    }

    //POST EXAMPLE
    public function store() {
        $required = ['name', 'email', 'password'];

        $input = Request::input();

        if (Request::validateFields($required, $input, $this->userModel->columns)) {
            try {
                $this->userModel->create($input);
                return Response::success('User created.', $input);
            } catch (PDOException $e) {
                return Response::error();
            }
        }
    }
}
