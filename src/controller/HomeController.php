<?php

namespace App\Controller;

use App\Model\Missions;
use Core\Controller;

class HomeController extends AppController
{

    private Missions $Missions;
    public function __construct()
    {
        parent::__construct();

        // $this->model = $this->getModel();
        if (!$this->auth->isLogged()) {
            $this->redirect('login');
        }

        $this->Missions = $this->getModel('missions');
    }

    function index()
    {
        $this->Missions->findMissionsByUserId($this->auth->getUserId());

        $pageTitle = "Your missions";

        $this->render('home/index', compact('pageTitle'));
    }
}
