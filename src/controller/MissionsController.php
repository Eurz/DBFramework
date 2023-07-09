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
    }

    public function index()
    {

        $missions = $this->model->findAll();
        $pageTitle = 'Missions';
        $this->render('missions/index', compact('pageTitle', 'missions'));
    }
    /**
     * View mission details
     */
    public function view($id)
    {
        $mission = $this->model->findById($id);

        $pageTitle = 'Mission : <i>' . $mission->title . '</i>';
        $this->render('missions/view', compact('pageTitle', 'mission'));
    }

    /**
     * ADD MISSION
     */
    public function add($action = 'default')
    {

        if (!$this->session->exist('mission')) {
            $this->session->set('mission', [], $action);
        }

        // Initialisation
        $pageTitle = 'Missions';
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');

        // Form
        $form = new Forms();

        // Form's data
        switch ($action) {
            case 'hidings':
                $pageTitle = 'Mission : Add an hiding';
                $countryId = $this->session->getValue('default', 'countryId');
                $hidings = $this->Hidings->findBy('countryId', $countryId);

                if (!$hidings) {
                    $this->messageManager->setError('There is no hiding');
                }

                $form
                    ->addRow('hidingId', [], 'Hiding', 'select', true, $hidings);
                $view = 'missions/addHiding';
                $options = compact('pageTitle', 'form');

                break;

            case 'contacts':
                $pageTitle = 'Mission : Add contact(s)';
                $countryId = $this->session->getValue('default', 'countryId');
                $contacts = $this->model->findContactsForMission($countryId);

                $view = 'missions/addUsers';
                if (!$contacts) {
                    $this->messageManager->setError('There \'s no contact(s) available(s) for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $form
                        ->addRow('contacts', [], 'Contact(s)', 'select:multiple', true, $contacts, ['minValue' => 1]);

                    $options = compact('pageTitle', 'form');
                }


                break;
            case 'agents':
                $pageTitle = 'Mission : Add agent(s)';

                $message = 'Choose agent(s) for mission';
                $agents = $this->Users->findByKeys('id', 'fullName', 'agent');

                if ($agents === false) {
                    $this->messageManager->setError('There \'s no agent(s) available(s) in your database for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $form
                        ->addRow('agents', [], 'Agent(s)', 'select:multiple', true, $agents, ['minValue' => 1]);
                    $options = compact('pageTitle', 'form', 'message');
                }
                $view = 'missions/addUsers';

                break;


            case 'targets':
                $pageTitle = 'Mission : Add target(s)';

                $agentsIds = $this->session->getValue('agents');
                $targets = $this->model->findTargetsForMission($agentsIds);

                $form
                    ->addRow('targets', [], 'Target(s)', 'select:multiple', true, $targets, ['minValue' => 1]);

                $view = 'missions/addUsers';
                $options = compact('pageTitle', 'form');

                break;


            case 'end':
                $data = $this->session->get('mission');

                $response = $this->model->insert($data);

                if ($response) {
                    $this->session->reset();
                }

                $this->redirect('missions');
                break;

            default:

                $status = $this->Attributes->findByKeys('id', 'title', 'status');
                $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');
                $missionTypes = $this->Attributes->findByKeys('id', 'title', 'missionType');
                $form
                    ->addRow('missionTypeId', [], 'Type', 'select', true, $missionTypes, ['notBlank' => true])
                    ->addRow('title', '', 'Title', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('description', '', 'Description', 'textarea')
                    ->addRow('status', '', 'Status', 'select', true, $status)
                    ->addRow('codeName', '', 'CodeName', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('countryId', [], 'Country', 'select', true, $countries)
                    ->addRow('specialityId', [], 'Required speciality', 'select', true, $specialities)
                    ->addRow('startDate', date('Y-m-d'), 'Start date', 'input:date')
                    ->addRow('endDate', date('Y-m-d'), 'End date', 'input:date');


                $this->session->delete('mission');

                $view = 'missions/form';
                $options = compact('pageTitle', 'form');

                break;
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            if ($action === 'default') {
                $this->session->merge($data, $action);
            } else {
                $this->session->merge($data);
            }


            switch ($action) {
                case 'hidings':
                    $this->redirect('missions/add/contacts');
                    break;
                case 'contacts':
                    $this->redirect('missions/add/agents');
                    break;
                case 'agents':
                    $specialityId = $this->session->getValue('default', 'specialityId');
                    $agents = $this->session->getValue('agents');
                    $agentsWithSameSpeciality = $this->Users->findAgentsWithSpecialtities($agents, $specialityId);
                    $missionSpeciality = $this->Attributes->findById($specialityId);


                    if (empty($agentsWithSameSpeciality)) {
                        $this->messageManager->setError('One or more agent should have the speciality "' . $missionSpeciality->title . '"');
                        $this->redirect('missions/add/agents');
                    }

                    $this->redirect('missions/add/targets');
                    break;
                case 'targets':
                    $this->redirect('missions/add/end');
                    break;

                case 'end':
                    $this->session->reset();
                    break;
                default:
                    $this->redirect('missions/add/hidings');

                    break;
            }
        }

        $this->render($view, $options);
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
            ->addRow('description', $mission->description, 'Description', 'textarea')
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

    /**
     * Delete a user
     * @param int $id - User's ID
     */
    public function delete($id)
    {
        $mission = $this->model->findById($id);

        if (!$mission) {
            $this->redirect('missions');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->deleteMission($id);
            }
            $this->redirect('missions');
        }

        $pageTitle = 'Delete a mission';
        $this->render('missions/delete', compact('pageTitle', 'mission'));
    }
}
