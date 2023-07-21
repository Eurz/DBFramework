<?php

namespace App\Controller;

use App\Model\Attributes;
use Core\Forms\Forms;

class HidingsController extends AppController
{
    private Attributes $Attributes;
    protected $roles = 'ROLE_ADMIN';

    public function __construct()
    {
        parent::__construct();
        if (!$this->auth->isLogged()) {
            $this->redirect('login');
        }

        if (!$this->auth->grantedAccess($this->roles)) {
            $this->redirect('home');
        }
        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('Attributes');
    }

    /**
     * Read all attributes
     */
    public function index()
    {
        $filtersOptions = $paginationParams = $this->formFiltersUsers();

        $hidingsPerPage = 4;
        $filtersOptions['hidingsPerPage'] = $hidingsPerPage;
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;


        $filtersOptions['offset'] = ($page - 1) * $hidingsPerPage;

        $hidings = $this->model->findAll($filtersOptions);
        $hidingsTypes = $this->Attributes->findAll('hiding');
        $countries = $this->Attributes->findAll('country');

        $nbHidings = $this->model->getNbHidings();
        $nbPages = ceil($nbHidings / $hidingsPerPage);

        $pageTitle = 'Hidings';
        $pagination = $this->pagination($nbPages, $paginationParams);

        $this->render('hidings/index', compact('pageTitle', 'hidings', 'countries', 'hidingsTypes', 'filtersOptions', 'pagination'));
    }



    function add()
    {
        $pageTitle = 'Add an hiding';
        $message = 'Firstly, you must create attributes';
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $hidingTypes = $this->Attributes->findByKeys('id', 'title', 'hiding');

        $form = null;

        if (!$countries || !$hidingTypes) {

            if (empty($countries)) {
                $message .= "<div>Country</div>";
            }
            if (!$hidingTypes) {
                $message .= "<div>Hiding type</div>";
            }
            // $this->messageManager->setError('', 'error');
            $viewPath = 'hidings/actions';
            $options =  compact('pageTitle', 'message');
        } else {
            $form = new Forms();
            $form
                ->addRow('code', '', 'Code', 'input:text', true, null, ['notBlank' => false])
                ->addRow('address', '', 'Address', 'input:text', true, null, ['notBlank' => true])
                ->addRow('typeId', '', 'Type', 'select', true, $hidingTypes, ['notBlank' => true])
                ->addRow('countryId', '', 'Country', 'select', true, $countries, ['notBlank' => true]);

            if ($form->isSubmitted() && $form->isValid()) {
                $hiding = $form->getData();
                $response = $this->model->insert($hiding);

                if ($response) {
                    $id = $this->model->lastInsertId();

                    $this->redirect('hidings/edit/' . $id);
                }
            }
            $viewPath = 'hidings/form';
            $options =  compact('pageTitle', 'form');
        }


        $this->render($viewPath, $options);
    }

    public function edit($id)
    {
        $hiding = $this->model->findById($id);
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $hidingTypes = $this->Attributes->findByKeys('id', 'title', 'hiding');


        if (!$hiding) {
            $this->redirect('hidings');
        }

        $form = new Forms();
        $form
            ->addRow('code', $hiding->code, 'Code', 'input:text', true, null, ['notBlank' => true])
            ->addRow('address', $hiding->address, 'Address', 'input:text', true, null, ['notBlank' => true])
            ->addRow('typeId', $hiding->typeId, 'Type', 'select', true, $hidingTypes, ['notBlank' => true])
            ->addRow('countryId', $hiding->countryId, 'Country', 'select', true, $countries, ['notBlank' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->update($id, $data);

            if ($response) {
                $this->redirect('hidings/edit/' . $id);
            }
        }

        $pageTitle = 'Edit an hiding';

        $this->render('hidings/form', compact('pageTitle', 'form', 'hiding'));
    }

    public function delete($id)
    {

        $message = '';
        $hiding = $this->model->findById($id);
        if (!$hiding) {
            $message = 'This hiding doesn\'t exist';
            $this->redirect('hidings');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);

                if ($response) {
                    $message = 'Hiding deleted in database';
                    $this->redirect('hidings');
                }
            } else {
                $message = 'Hiding deleted in database';
                $this->redirect('hidings');
            }
        }

        $pageTitle = 'Delete an hiding';
        $this->render('hidings/delete', compact('pageTitle', 'hiding', 'message'));
    }

    /**
     * Form filters for hidings
     */
    private function formFiltersUsers()
    {
        $args = array(
            'country' => FILTER_VALIDATE_INT,
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
        );


        return filter_input_array(INPUT_GET, $args);
    }
}
