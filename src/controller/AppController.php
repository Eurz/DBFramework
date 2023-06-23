<?php

namespace App\Controller;

use Core\Application;
use Core\Authentication;
use Core\Controller;
use Core\Forms;
use Core\Http;
use Core\Messages;

/**
 * Default controller for Application
 */
class AppController extends Controller
{

    // protected $modelName;   
    protected $auth;
    protected $messages;

    public function __construct()
    {

        parent::__construct();
        // $this->auth = new Authentication(Application::getDb());
        // if ($this->auth->isLogged()) {
        //     return;
        // }

        // $this->login();
        $this->messages = new Messages();
    }

    public function login()
    {
        $this->redirect('login');
    }
}
