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

    //GET ALL USERS EXAMPLE
    public function list() {
        try {
            $users = $this->userModel->findAll();
            return Response::success($users);
        } catch (PDOException $e) {
            return Response::error();
        }
    }

    //FIND A SPECIFIC USER BY ID EXAMPLE
    public function findById($id) {
        try {
            $users = $this->userModel->find('id', $id);
            if (empty($users)) {
                return Response::error(404, [], 'not_found');
            }
            return Response::success($users);
        } catch (PDOException $e) {
            return Response::error();
        }
    }

    //POST EXAMPLE
    public function store() {
        $required = ['name', 'email', 'password'];

        $input = Request::input();

        //VALIDATE FIELDS WITH MODEL
        if (Request::validateFields($required, $input, $this->userModel->columns)) {
            try {
                //FIND A SPECIFIC USER BY EMAIL (or any column in model)
                $users = $this->userModel->find('email', $input['email']);
                if (!empty($users)) {
                    return Response::error(409, [], 'duplicated');
                }
                $this->userModel->create($input);
                return Response::success($input);
            } catch (PDOException $e) {
                return Response::error();
            }
        }
    }
}
