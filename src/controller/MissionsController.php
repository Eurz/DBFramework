<?php

namespace App\Controller;

use App\Model\Attributes;
use App\Model\Hidings;
use App\Model\Users;
use App\Session;
use Core\Forms;

class MissionsController extends AppController
{
    protected Attributes $Attributes;
    protected Users $Users;
    protected Hidings $Hidings;
    protected Session $session;
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('attributes');
        $this->Users = $this->getModel('users');
        $this->Hidings = $this->getModel('hidings');

        $this->session = new Session('mission');
        // $this->session->delete('mission');
        // $this->session->reset();
    }

    public function index()
    {

        // $session = new Session();
        // $session->set('test', 'value test');
        $missions = $this->model->findAll();
        $pageTitle = 'Missions';
        $this->render('missions/index', compact('pageTitle', 'missions'));
    }

    public function add($action = 'default')
    {

        // $mission = $this->model->findById($id);
        $pageTitle = 'Missions';
        $countries = $this->Attributes->findKeyAndValue('id', 'title', 'country');

        $status = $this->Attributes->findKeyAndValue('id', 'title', 'status');
        $specialities = $this->Attributes->findKeyAndValue('id', 'title', 'speciality');
        // $this->session->set('missionAction', 'default');
        $form = new Forms();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->session->merge($data);
            switch ($action) {
                case 'hidings':
                    $this->redirect('missions/add/end');
                    break;
                case 'agent':
                    break;
                case 'end':
                    break;
                default:
                    $this->redirect('missions/add/hidings');
                    break;
            }

            // $response = $this->model->updateUser($id, $data);
            // if ($response) {
            //     $message = 'User saved in database';
            //     // var_dump($response);
            //     // $this->redirect('users');
            // }

        }


        switch ($action) {
            case 'hidings':
                $agents = $this->Users->findKeyAndValue('id', 'fullName', 'agent');
                $contacts = $this->Users->findKeyAndValue('id', 'fullName', 'contact');
                $targets = $this->Users->findKeyAndValue('id', 'fullName', 'target');
                $hidings = $this->Hidings->findKeyAndValue('id', 'code');

                $form
                    ->addRow('agents', [], 'Agent(s)', 'select:multiple', true, $agents)
                    ->addRow('contacts', [], 'Contact(s)', 'select:multiple', true, $contacts)
                    ->addRow('hidings', [], 'Hiding', 'select', true, $hidings)
                    ->addRow('targets', [], 'Target(s)', 'select:multiple', true, $targets);
                $this->render('missions/addAgent', compact('pageTitle', 'form'));

                break;
            case 'agent':
                var_dump('Ajout d\'agent');
                break;
            case 'end':
                $this->session->delete('mission');
                $this->redirect('missions');
                break;
            default:
                $form
                    ->addRow('status', '', 'Status', 'select', true, $status)
                    ->addRow('title', '', 'Title', 'input:text')
                    ->addRow('description', '', 'Description', 'textarea')
                    ->addRow('codeName', '', 'CodeName', 'input:text')
                    ->addRow('countryId', [], 'Country', 'select', true, $countries)
                    ->addRow('speciality', [], 'Required speciality', 'select', true, $specialities)
                    ->addRow('startDate', date('Y-m-d'), 'Start date', 'input:date')
                    ->addRow('endDate', date('Y-m-d'), 'End date', 'input:date');
                $this->render('missions/form', compact('pageTitle', 'form'));
                break;
        }
    }

    public function edit($id)
    {
        $mission = $this->model->findById($id);
        $countries = $this->Attributes->findKeyAndValue('id', 'title', 'country');
        $agents = $this->Users->findKeyAndValue('id', 'fullName', 'agent');
        $contacts = $this->Users->findKeyAndValue('id', 'fullName', 'contact');
        $targets = $this->Users->findKeyAndValue('id', 'fullName', 'target');
        $status = $this->Attributes->findKeyAndValue('id', 'title', 'status');
        $specialities = $this->Attributes->findKeyAndValue('id', 'title', 'speciality');

        $form = new Forms();

        $form
            ->addRow('status', $mission->status, 'Status', 'select', true, $status)
            ->addRow('title', $mission->title, 'Title', 'input:text')
            ->addRow('description', $mission->description, 'Description', 'input:text')
            ->addRow('codeName', $mission->codeName, 'CodeName', 'input:text')
            ->addRow('countryId', $mission->countryId, 'Country', 'select', true, $countries)
            ->addRow('speciality', $mission->countryId, 'Speciality', 'select', true, $specialities)
            ->addRow('startDate', $mission->startDate, 'Start date', 'input:date')
            ->addRow('endDate', $mission->endDate, 'End date', 'input:date');
        // ->addRow('agents', [], 'Agent(s)', 'select:multiple', true, $agents)
        // ->addRow('contacts', [], 'Contact(s)', 'select:multiple', true, $contacts)
        // ->addRow('targets', [], 'Target(s)', 'select:multiple', true, $targets);


        $pageTitle = 'Missions';
        $this->render('missions/form', compact('pageTitle', 'form'));
    }
}
