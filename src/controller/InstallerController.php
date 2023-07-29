<?php

namespace App\Controller;

use App\Model\Missions;
use App\Model\Users;
use Core\Application;
use Core\DBMaker;
use Core\Forms\Forms;

class InstallerController extends AppController
{

    private DBMaker $dbMaker;
    private Users $Users;
    public function __construct()
    {
        parent::__construct();
        $db = Application::getDb();
        $exist = $db->dbExist();
        $this->Users = $this->getModel('users');
        if ($exist) {
            $this->redirect('missions');
            die();
        }
    }

    function index()
    {


        $form = new Forms();
        $form
            ->addRow('firstName', '', 'First name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('lastName', '', 'Last name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('email', '', 'Email', 'input:email', true, null, ['notBlank' => true])
            ->addRow('password', '', 'Password', 'input:password', true, null, ['notBlank' => true]);


        $pageTitle = "Installation";

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data['password'] = $this->auth->hashPassword($data['password']);

            $this->dbMaker = new DBMaker();

            $createDb = $this->dbMaker->createDB();

            if ($createDb) {
                // $this->messageManager->setSuccess('Initialization completed');
                $this->dbMaker->createData();
                $this->messageManager->setSuccess('Application successfully installed');


                $response = $this->Users->insertUser($data, 'manager');
                if ($response !== false) {

                    $this->redirect('login');
                }
            }
        }
        $viewPath = 'home/index';

        $this->render($viewPath, compact('pageTitle', 'form'));
    }
}
