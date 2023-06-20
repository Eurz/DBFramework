<?php

namespace App\Controller;

use Core\Application;
use Core\Authentication;
use Core\Controller;
use Core\Forms;
use Core\Http;

/**
 * Default controller for Application
 */
class AppController extends Controller
{

    // protected $modelName;   
    protected $auth;

    public function __construct()
    {
        parent::__construct();
        // $this->auth = new Authentication(Application::getDb());
        // if ($this->auth->isLogged()) {
        //     return;
        // }

        // $this->login();
    }

    public function login()
    {
        $this->redirect('login');
    }
}
