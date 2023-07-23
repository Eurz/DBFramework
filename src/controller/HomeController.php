<?php

namespace App\Controller;

use App\Model\Missions;
use Core\Controller;
use Core\DBMaker;
use Core\Forms\Forms;

class HomeController extends AppController
{

    private Missions $Missions;
    private DBMaker $dbMaker;
    public function __construct()
    {
        parent::__construct();

        // $this->model = $this->getModel();
        // if (!$this->auth->isLogged()) {
        //     $this->redirect('login');
        // }

        // $this->dbMaker = new DBMaker();
    }

    function index()
    {
        // $form = new Forms();
        // $form
        //     ->addRow('host', 'localhost', 'Host', 'input:text', true, null, ['notBlank' => true])
        //     ->addRow('user', 'root', 'User', 'input:text', true, null, ['notBlank' => true])
        //     ->addRow('password', '', 'Password', 'input:text', true, null)
        //     ->addRow('name', '', 'DB name', 'input:text', true, null, ['notBlank' => true]);


        // $pageTitle = "Installation";

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $data = $form->getData();


        //     $response = $this->dbMaker->createDB($data);
        //     // if ($response) {
        //     //     $this->createTables();
        //     // }
        // }


        // $this->render('home/index', compact('pageTitle', 'form'));
    }

    private function createTables()
    {
        var_dump('creation des tables');
        $pageTitle = "Creation of tables";
        $this->render('home/index', compact('pageTitle'));
    }
}
