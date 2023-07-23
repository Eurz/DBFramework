<?php

namespace App\Controller;

use App\Model\Attributes;
use App\Model\Hidings;
use App\Model\Users;
use Core\Application;
use Core\Session;
use Core\Forms\Forms;
use Core\Messages;

class MissionsController extends AppController
{
    protected Attributes $Attributes;
    protected Users $Users;
    protected Hidings $Hidings;
    protected Session $session;
    protected $roles = 'ROLE_USER';
    public function __construct()
    {
        parent::__construct();
        $db = Application::getDb();
        $exist = $db->dbExist();
        if (!$exist) {
            // $this->dbInstall();
            $this->redirect('install');
            die();
        }

        if (!$this->auth->isLogged()) {
            $this->redirect('login');
            die();
        }


        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('attributes');
        $this->Users = $this->getModel('users');
        $this->Hidings = $this->getModel('hidings');

        $this->session = new Session('mission');
    }

    public function index()
    {

        $message = [];

        // SEARCH
        $searchParams = $this->searchForm();


        // FILTERS
        $filtersOptions = $paginationParams = $this->formFiltersMissions();

        $countries = $this->Attributes->findAll('country');
        $status = $this->Attributes->findAll('status');

        $missionsPerPage = 4;
        $filtersOptions['missionsPerPages'] = $missionsPerPage;
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

        $filtersOptions['offset'] = ($page - 1) * $missionsPerPage;

        $userId = null;
        $this->auth->grantedAccess('ROLE_USER');
        if ($this->auth->getUser() && $this->auth->getUser()->userType !== 'manager') {
            $userId = $this->auth->getUserId();
        }
        $missions = $this->model->findAll($filtersOptions, $searchParams, $userId);
        $nbMissions = $this->model->getNbMissions();


        $nbPages = ceil($nbMissions / $missionsPerPage);

        $pageTitle = 'Missions';

        if ($nbMissions > $missionsPerPage) {
            $pagination = $this->pagination($nbPages, $paginationParams);
        } else {
            $pagination = null;
        }


        $this->render('missions/index', compact('pageTitle', 'missions', 'countries', 'status', 'filtersOptions', 'pagination', 'message'));
    }

    /**
     * Get keyword from query search
     * @return array[string] - Keys from search
     */
    public function searchForm()
    {
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($q)) {
            $keywords = explode(' ', $q);
            return array_filter(
                $keywords,
                function ($keyword) {
                    if (strlen($keyword) > 3) {
                        return $keyword;
                    }
                }
            );
        }

        return [];
    }

    /**
     * View mission details
     */
    public function view($id)
    {
        $mission = $this->model->findById($id);

        if (!$mission) {
            $this->redirect('missions');
        }

        $pageTitle = 'Mission : <i>' . $mission->title . '</i>';
        $this->render('missions/view', compact('pageTitle', 'mission'));
    }

    /**
     * ADD MISSION
     */
    public function add($action = 'default')
    {
        if (!$this->auth->grantedAccess('ROLE_ADMIN')) {
            $this->redirect('missions');
        }


        // Initialisation
        $pageTitle = 'Missions';
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        // Form
        $form = new Forms();
        if (isset($_POST['cancelMission'])) {
            $this->session->delete('Mission');
            $this->messageManager->setSuccess('You canceled adding a mission');
            $this->redirect('missions');
        }
        // Form's data
        switch ($action) {
            case 'hidings':
                $pageTitle = 'Mission : Add an hiding';
                $countryId = $this->session->getValue('default', 'countryId');
                $hidings = $this->Hidings->findBy('countryId', $countryId);

                if (!$hidings) {
                    $country = $this->Attributes->findById($countryId);
                    $this->messageManager->setError('There is no hiding for selected country : ' . $country->title);
                    $message = "Please add a new hiding for the selected country or choose another country for this mission to create it. ";
                    $options = compact('pageTitle', 'action', 'message');
                } else {

                    $form
                        ->addRow('hidingId', [], 'Hiding', 'select', true, $hidings);
                    $options = compact('pageTitle', 'form');
                }

                $viewPath = 'missions/addHiding';

                break;

            case 'contacts':
                $pageTitle = 'Mission : Add contact(s)';
                $countryId = $this->session->getValue('default', 'countryId');
                $contacts = $this->model->findContactsForMission($countryId);

                $viewPath = 'missions/addUsers';
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
                if (!$agents) {
                    $this->messageManager->setError('There \'s no agent(s) available(s) in your database for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $form
                        ->addRow('agents', [], 'Agent(s)', 'select:multiple', true, $agents, ['minValue' => 1]);
                    $options = compact('pageTitle', 'form', 'message');
                }
                $viewPath = 'missions/addUsers';

                break;


            case 'targets':
                $pageTitle = 'Mission : Add target(s)';

                $agentsIds = $this->session->getValue('agents');
                $targetsMission = $this->model->findTargetsForMission($agentsIds);

                $targets = $this->Users->findByKeys('id', 'fullName', $targetsMission->targets);

                if (!$targetsMission->targets) {
                    $this->messageManager->setError('There \'s no target(s) available(s) in your database for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $form
                        ->addRow('targets', [], 'Target(s)', 'select:multiple', true, $targets, ['minValue' => 1]);
                    $options = compact('pageTitle', 'form');
                }

                $viewPath = 'missions/addUsers';

                break;


            case 'end':
                $data = $this->session->get('mission');

                $response = $this->model->insert($data);

                if ($response) {
                    $this->session->delete('mission');
                }

                $this->redirect('missions');
                break;

            default:
                $message = 'Firstly, you must add attributes:<br> ';
                $status = $this->Attributes->findByKeys('id', 'title', 'status');
                $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');
                $missionTypes = $this->Attributes->findByKeys('id', 'title', 'missionType');

                if (!$specialities || !$status || !$missionTypes || empty($countries)) {
                    $requiresAttributes = [];
                    if (!$status) {
                        $requiresAttributes[] = '
                            <div> - status 
                            <a href="/attributes/add/status" target="_blank" class="btn btn-primary">Add a status</a>
                            </div>';
                    }
                    if (!$specialities) {
                        $requiresAttributes[] = '
                            <div> - speciality
                            <a href="/attributes/add/speciality" target="_blank" class="btn btn-primary">Add a speciality</a>
                            </div>';
                    }
                    if (!$missionTypes) {
                        $requiresAttributes[] = '
                            <div> - mission type
                            <a href="/attributes/add/status" target="_blank" class="btn btn-primary">Add a mission type</a>
                            </div>';
                    }
                    if (!$countries) {
                        $requiresAttributes[] = '
                            <div> - countries
                            <a href="/attributes/add/country" target="_blank" class="btn btn-primary">Add a country</a>
                            </div>';
                    }

                    $message .= implode('', $requiresAttributes);
                    $options = compact('pageTitle', 'message');
                } else {
                    $form
                        ->addRow('missionTypeId', [], 'Type', 'select', true, $missionTypes, ['notBlank' => true])
                        ->addRow('status', '', 'Status', 'select', true, $status)
                        ->addRow('title', '', 'Title', 'input:text', true, null, ['notBlank' => true])
                        ->addRow('description', '', 'Description', 'textarea')
                        ->addRow('codeName', '', 'CodeName', 'input:text', true, null, ['notBlank' => true])
                        ->addRow('countryId', [], 'Country', 'select', true, $countries)
                        ->addRow('specialityId', [], 'Required speciality', 'select', true, $specialities)
                        ->addRow('startDate', date('Y-m-d'), 'Start date', 'input:date')
                        ->addRow('endDate', date('Y-m-d'), 'End date', 'input:date');

                    $options = compact('pageTitle', 'form', 'message');
                }

                $this->session->delete('mission');

                $viewPath = 'missions/form';

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

        $this->render($viewPath, $options);
    }
    /**
     * Edit a mission
     */
    public function edit($id, $action = 'default')
    {

        if (!$this->auth->grantedAccess('ROLE_ADMIN')) {
            $this->redirect('missions');
        }

        if (isset($_POST['cancelMission'])) {
            $this->session->delete('Mission');
            $this->messageManager->setSuccess('You canceled adding a mission');
            $this->redirect('missions');
        }

        $pageTitle = 'Missions';
        $mission = $this->model->findById($id);
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $agents = $this->Users->findByKeys('id', 'fullName', 'agent');
        $contacts = $this->Users->findByKeys('id', 'fullName', 'contact');
        $targets = $this->Users->findByKeys('id', 'fullName', 'target');
        $status = $this->Attributes->findByKeys('id', 'title', 'status');
        $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');


        $form = new Forms();

        // Form's data
        switch ($action) {
            case 'hiding':
                $pageTitle = 'Mission : Add an hiding';
                $countryId = $this->session->getValue('default', 'countryId');
                $hidings = $this->Hidings->findBy('countryId', $countryId);

                if (!$hidings) {
                    $this->messageManager->setError('There is no hiding');
                }

                $form
                    ->addRow('hidingId', $mission->hidingId, 'Hiding', 'select', true, $hidings);
                $viewPath = 'missions/addHiding';
                $options = compact('pageTitle', 'form');

                break;

            case 'contact':

                $pageTitle = 'Mission : Add contact(s)';
                $countryId = $this->session->getValue('default', 'countryId');
                $contacts = $this->model->findContactsForMission($countryId);

                $viewPath = 'missions/addUsers';
                if (!$contacts) {
                    $this->messageManager->setError('There \'s no contact(s) available(s) for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $contactsMission = $this->model->findByKeys('id', 'fullName', $mission->contacts);
                    $form
                        ->addRow('contacts', $contactsMission, 'Contact(s)', 'select:multiple', true, $contacts, ['minValue' => 1]);

                    $options = compact('pageTitle', 'form');
                }


                break;
            case 'agent':
                $pageTitle = 'Mission : Add agent(s)';

                $message = 'Choose agent(s) for mission';
                $agents = $this->Users->findByKeys('id', 'fullName', 'agent');

                if ($agents === false) {
                    $this->messageManager->setError('There \'s no agent(s) available(s) in your database for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $agentsMission = $this->model->findByKeys('id', 'firstName', $mission->agents);
                    $form
                        ->addRow('agents', $agentsMission, 'Agent(s)', 'select:multiple', true, $agents, ['minValue' => 1]);
                    $options = compact('pageTitle', 'form', 'message');
                }
                $viewPath = 'missions/addUsers';

                break;


            case 'target':
                $pageTitle = 'Mission : Add target(s)';

                $agentsIds = $this->session->getValue('agents');
                $data = $this->model->findTargetsForMission($agentsIds);

                if ($data->targets === false) {

                    $this->messageManager->setError('There \'s no target(s) available(s) in your database for this mission');
                    $options = compact('pageTitle', 'action');
                } else {
                    $targetsMission = $this->model->findByKeys('id', 'firstName', $mission->targets);

                    $form
                        ->addRow('targets', $targetsMission, 'Target(s)', 'select:multiple', true, $targets, ['minValue' => 1]);
                    $options = compact('pageTitle', 'form');
                }

                $viewPath = 'missions/addUsers';

                break;


            case 'end':
                $data = $this->session->get('mission');
                $response = $this->model->update($id, $data);

                if ($response) {
                    $this->session->delete('mission');
                }

                $this->redirect('missions');
                break;

            default:

                $status = $this->Attributes->findByKeys('id', 'title', 'status');
                $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');
                $missionTypes = $this->Attributes->findByKeys('id', 'title', 'missionType');

                $form

                    ->addRow('missionTypeId', $mission->missionTypeId, 'Type', 'select', true, $missionTypes, ['notBlank' => true])
                    ->addRow('title', $mission->title, 'Title', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('description', $mission->description, 'Description', 'textarea')
                    ->addRow('status', $mission->statusId, 'Status', 'select', true, $status)
                    ->addRow('codeName', $mission->codeName, 'CodeName', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('countryId', $mission->countryId, 'Country', 'select', true, $countries)
                    ->addRow('specialityId', $mission->specialityId, 'Required speciality', 'select', true, $specialities)
                    ->addRow('startDate', $mission->startDate, 'Start date', 'input:date')
                    ->addRow('endDate', $mission->endDate, 'End date', 'input:date');


                $this->session->delete('mission');

                $viewPath = 'missions/form';
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
                case 'hiding':
                    $this->redirect('missions/edit/' . $id . '/contact');
                    break;
                case 'contact':
                    $this->redirect('missions/edit/' . $id . '/agent');
                    break;
                case 'agent':
                    $specialityId = $this->session->getValue('default', 'specialityId');
                    $agents = $this->session->getValue('agents');
                    $agentsWithSameSpeciality = $this->Users->findAgentsWithSpecialtities($agents, $specialityId);
                    $missionSpeciality = $this->Attributes->findById($specialityId);

                    if (empty($agentsWithSameSpeciality)) {
                        $this->messageManager->setError('One or more agent should have the speciality "' . $missionSpeciality->title . '"');
                        $this->redirect('missions/edit/' . $id . '/agent');
                    }

                    $this->redirect('missions/edit/' . $id . '/target');
                    break;
                case 'target':
                    $this->redirect('missions/edit/' . $id . '/end');
                    break;

                case 'end':
                    break;
                default:
                    $this->redirect('missions/edit/' . $id . '/hiding');

                    break;
            }
        }

        $this->render($viewPath, $options);
    }

    /**
     * Delete a mission
     * @param int $id - Mission's ID
     */
    public function delete($id)
    {

        if (!$this->auth->grantedAccess('ROLE_ADMIN')) {
            $this->redirect('missions');
        }

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

    /**
     * Form filters for missions
     */
    private function formFiltersMissions()
    {
        $args = array(
            'country' => FILTER_VALIDATE_INT,
            'status' => FILTER_VALIDATE_INT,
            'sortBy' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_DEFAULT,
                'options' => array(
                    'regexp' => '#[\w]#'
                ),
            ),
            'orderBy' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_DEFAULT,
                'options' => array(
                    'regexp' => '#^ASC|DESC$#'
                ),
            ),
            'q' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_DEFAULT,
                'options' => array(
                    'regexp' => '#[\w]#'
                ),
            ),

        );


        return filter_input_array(INPUT_GET, $args);
    }
}
