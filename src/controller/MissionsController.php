<?php

namespace App\Controller;

use App\Model\Attributes;
use App\Model\Hidings;
use App\Model\Users;
use Core\Session;
use Core\Forms\Forms;

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
        $missions = $this->model->findAll();
        $pageTitle = 'Missions';
        $this->render('missions/index', compact('pageTitle', 'missions'));
    }

    public function add($action = 'default')
    {
        // Initialisation
        // $mission = $this->model->findById($id);
        $pageTitle = 'Missions';
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');

        // Data's form
        $status = $this->Attributes->findByKeys('id', 'title', 'status');
        $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');

        // Form
        // $this->session->set('missionAction', 'default');
        $form = new Forms();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->session->merge($data);
            switch ($action) {
                case 'hidings':
                    $this->redirect('missions/add/agents');
                    break;
                case 'agents':
                    $this->redirect('missions/add/contacts');
                    break;
                case 'contacts':
                    $this->redirect('missions/add/targets');
                    break;
                case 'targets':
                    $this->redirect('missions/add/end');
                    break;
                case 'end':
                    break;
                default:
                    $this->session->reset();

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
                var_dump($this->session->get('mission'));
                $countryId = $this->session->getValue('countryId');
                $hidings = $this->Hidings->findBy('countryId', $countryId);

                $form
                    ->addRow('hidings', [], 'Hiding', 'select', true, $hidings);

                $this->render('missions/addHiding', compact('pageTitle', 'form'));

                break;
            case 'agents':
                $message = 'Choose agent(s) for mission';
                $agents = $this->Users->findByKeys('id', 'fullName', 'agent');

                $form
                    ->addRow('agents', [], 'Agent(s)', 'select:multiple', true, $agents);


                $this->render('missions/addAgent', compact('pageTitle', 'form', 'message'));

                break;
            case 'contacts':
                $countryId = $this->session->getValue('countryId');
                $contacts = $this->model->findContacts($this->session->getValue('agents'));

                $form
                    ->addRow('contacts', [], 'Contact(s)', 'select:multiple', true, $contacts);

                $this->render('missions/addContact', compact('pageTitle', 'form'));

                break;
            case 'targets':
                $targets = $this->Users->findByKeys('id', 'fullName', 'target');

                $form
                    ->addRow('targets', [], 'Target(s)', 'select:multiple', true, $targets);

                $this->render('missions/addAgent', compact('pageTitle', 'form'));

                break;
            case 'end':
                $this->session->delete('mission');
                $this->redirect('missions');
                break;
            default:
                $form
                    ->addRow('title', '', 'Title', 'input:text')
                    ->addRow('description', '', 'Description', 'textarea')
                    ->addRow('status', '', 'Status', 'select', true, $status)
                    ->addRow('codeName', '', 'CodeName', 'input:text')
                    ->addRow('countryId', [], 'Country', 'select', true, $countries)
                    ->addRow('speciality', [], 'Required speciality', 'select', true, $specialities)
                    ->addRow('startDate', date('Y-m-d'), 'Start date', 'input:date')
                    ->addRow('endDate', date('Y-m-d'), 'End date', 'input:date');
                $this->session->reset();

                $this->render('missions/form', compact('pageTitle', 'form'));
                break;
        }
    }

    public function edit($id)
    {
        $mission = $this->model->findById($id);
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $agents = $this->Users->findByKeys('id', 'fullName', 'agent');
        $contacts = $this->Users->findByKeys('id', 'fullName', 'contact');
        $targets = $this->Users->findByKeys('id', 'fullName', 'target');
        $status = $this->Attributes->findByKeys('id', 'title', 'status');
        $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');

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
