<?php

namespace App\Controller;

use Core\Application;
use Core\Authentication;
use Core\Controller;
use Core\DBMaker;
use Core\Forms\Forms;
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
    protected $user;
    private DBMaker $dbMaker;


    public function __construct()
    {
        parent::__construct();
        $db = Application::getDb();
        $exist = $db->dbExist();
        $this->messageManager = new Messages();

        if (!$exist) {
            $this->dbInstall();
            die();
        }

        $this->auth = new Authentication(Application::getDb());
    }


    public function dbInstall()
    {
        $form = new Forms();
        // $form
        //     ->addRow('host', 'localhost', 'Host', 'input:text', true, null, ['notBlank' => true])
        //     ->addRow('user', 'root', 'User', 'input:text', true, null, ['notBlank' => true])
        //     // ->addRow('password', '', 'Password', 'input:text', true, null)
        //     // ->addRow('name', '', 'DB name', 'input:text', true, null, ['notBlank' => true])
        // ;


        $pageTitle = "Installation";

        if ($form->isSubmitted()) {
            // $data = $form->getData();
            $this->dbMaker = new DBMaker();

            $response = $this->dbMaker->createDB();

            if ($response) {
                $this->messageManager->setSuccess('Initialization completed');
                $this->dbMaker->createData();
                $this->messageManager->setSuccess('Application successfully installed');
                $this->redirect('login');
            }
        }
        $viewPath = 'home/index';

        $this->render($viewPath, compact('pageTitle', 'form'));
    }


    /**
     * Users form login
     */
    public function login()
    {

        if (!$this->auth->isLogged()) {
            $form = new Forms();
            $form
                ->addRow('email', '', 'Email', 'input:email', true, null)
                ->addRow('password', '', 'Password', 'input:password', true, null);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $email = $data['email'];
                $password = $data['password'];
                if ($this->auth->login($email, $password) !== false) {
                    $this->redirect('missions');
                } else {
                    $this->messageManager->setError('Incorrect email or password');
                }
            }

            $pageTitle = 'Login page';
            $this->render('users/login', compact('pageTitle', 'form'));
            return;
        }

        $this->redirect('missions');
    }

    /**
     * Logoout user
     */
    public function logout()
    {
        $this->messageManager->setSuccess('You\'ve been logout successfully');
        $this->auth->logout();
        $this->redirect('login');
    }


    /**
     * Make an html pagination
     * @param int $nbPages - Number of pages for pagination
     * @param array $params - Params url for pagination's links
     * return string $html
     */
    public function pagination(int $nbPages, array $params): string
    {
        $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

        $urlParts = [];
        foreach ($params as $key => $value) {
            if ($value) {
                $urlParts[] = $key . '=' . $value;
            }
        }
        $url = '?' . implode('&', $urlParts);
        $html = '<nav aria-label="Users pagination">';

        $html .= '<ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $nbPages; $i++) {
            $active = $i === (int)$currentPage ? 'active' : null;
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '&page=' . $i . '">' .  $i . '</a></li>';
        }

        $html .= '</ul>';
        $html  .= '</nav>';

        return $html;
    }
    /**
     * Test
     */
    // public function isAdmin()
    // {
    //     if (!$this->auth->isAdmin()) {
    //         $pageTitle = 'Accès refusé';
    //         $message = 'You must be an admin to access this page';
    //         $this->render('error', compact('pageTitle', 'message'));
    //         exit();
    //     }
    // }
}
