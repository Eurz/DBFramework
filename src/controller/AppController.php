<?php

namespace App\Controller;

use Core\Application;
use Core\Authentication;
use Core\Controller;
use Core\Forms\Forms;
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
        if (!$this->auth->isLogged()) {
            // $this->login();
            // $this->redirect('login');
        }
    }

    public function login()
    {

        $form = new Forms();
        $form->addRow('email', '', 'Email', 'input:email', true, null, ['notBlank' => true]);
        $form->addRow('password', '', 'Password', 'input:password', true, null, ['notBlank' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];
            if ($this->auth->login($email, $password)) {
                $this->redirect('home');
            }
        }

        $pageTitle = 'Login page';
        $this->render('users/form', compact('pageTitle', 'form'));
    }
}
