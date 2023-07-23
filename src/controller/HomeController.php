<?php

namespace App\Controller;

use App\Model\Missions;
use Core\Application;
use Core\DBMaker;
use Core\Forms\Forms;

class HomeController extends AppController
{

    private Missions $Missions;
    private DBMaker $dbMaker;
    public function __construct()
    {
        parent::__construct();
        $db = Application::getDb();
        if ($db->dbExist()) {
            $this->redirect('missions');
        }
    }

    function index()
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

    private function createTables()
    {
        var_dump('creation des tables');
        $pageTitle = "Creation of tables";
        $this->render('home/index', compact('pageTitle'));
    }
}
