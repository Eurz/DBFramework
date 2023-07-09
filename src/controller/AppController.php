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
    protected $messageManager;
    protected $itemName = 'Test';

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Authentication(Application::getDb());
        $this->messageManager = new Messages();
        // var_dump($this->auth->isLogged());
        // die();
        if (!$this->auth->isLogged()) {
            // $this->redirect('login');
        }
    }

    public function test()
    {
    }
}
