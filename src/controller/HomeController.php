<?php

namespace App\Controller;

use Core\Controller;

class HomeController extends AppController
{

    public function __construct()
    {
        parent::__construct();

        // $this->model = $this->getModel();
    }

    function index()
    {

        $pageTitle = "Your dashboard";

        $userId = $this->auth->getUserId();

        $usersModel = $this->getModel('users');
        var_dump($userId);
        $user = $usersModel->findById($userId);

        $this->render('home/index', compact('pageTitle', 'user'));
    }
}
