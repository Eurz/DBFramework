<?php

namespace App\Controller;

use Core\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    function index(): void
    {

        $pageTitle = "Your dashboard";

        $this->render('home/index', compact('pageTitle'));
    }
}
