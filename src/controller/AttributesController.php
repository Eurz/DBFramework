<?php

namespace App\Controller;

use Core\Forms\Forms;
use Core\Session;
use stdClass;

class AttributesController extends AppController
{
    private array $types = [
        'country' => 'Country',
        'nationality' => 'Nationalities',
        'hiding' => 'Hidings type',
        'speciality' => 'Specialities',
        'status' => 'Missions status',
        'missionType' => 'Missions types',
        'userType' => 'User type'
    ];

    private Session $session;
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
        $this->session = new Session('attributesForm');
    }

    /**
     * Read all attributes
     */
    public function index()
    {
        $filters = $this->formFilter();

        $formFilter = $filters->formFilter;
        $filter = $filters->filter;

        $attributes = $this->model->findAll($filter);

        $types = $this->types;
        $pageTitle = 'Missions attributes';
        $this->render('attributes/index', compact('pageTitle', 'attributes', 'types', 'formFilter'));
    }

    /**
     * Return a std class with forms filter and current filter selected
     * @return stdClass 
     */
    private function formFilter()
    {
        $filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT);

        $session = new Session('attributesFilter');
        if ($filter) {
            if (!array_key_exists($filter, $this->types)) {
                $filter =  null;
            }

            $session->set('attributesFilter', $filter);
            $this->redirect('attributes');
        }

        $formFilter = new Forms();

        $formFilter
            ->addRow('filter', $session->get('attributesFilter'), 'Filter by', 'select', false, $this->types);

        return (object)['formFilter' => $formFilter, 'filter' => $session->get('attributesFilter')];
    }

    /**
     * Read a single attribute
     */
    public function view($id)
    {
        $attribute = $this->model->findById($id);

        $pageTitle = 'View an attribute';
        $this->render('attributes/view', compact('pageTitle', 'attribute'));
    }

    /**
     * Create an attribute
     * @param string $type
     */
    public function add($type)
    {

        if (!$this->typeExist($type)) {
            $this->messageManager->setError('Please select a valid type of attribute');
            $this->redirect('attributes');
        }

        $types = $this->types;
        $pageTitle = 'Add an attribute : ' . $type;

        $form = new Forms();
        $form
            ->addRow('title', '', 'Title', 'input:text', true, null, ['notBlank' => true]);

        if ($type === 'nationality') {
            $attributesCountries = $this->model->findAll('country');
            $countries = $this->model->findByKeys('id', 'title', $attributesCountries);

            if (!$attributesCountries) {
                $this->messageManager->setSuccess('You must create countries to be able to add a nationality');
            } else {
                $form
                    ->addRow('attribute', null, 'Linked country', 'select', true, $countries, ['notBlank' => true]);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data['type'] = $type;

            $response = $this->model->insert($data);

            if ($response) {

                $id = $this->model->lastInsertId();

                $this->redirect('attributes/edit/' . $id);
            }
        }

        $this->render('attributes/form', compact('pageTitle', 'types', 'form'));
    }

    /**
     * Edit an attribute
     * @param int $id - Attribute's ID
     */
    public function edit($id)
    {

        $types = $this->types;
        $attribute = $this->model->findById($id);
        // $attributes = $this->model->findAll();

        if ($attribute === false) {
            $this->redirect('attributes');
        }

        $form = new Forms();
        $form
            ->addRow('title', $attribute->title, 'Title', 'input:text', true, null, ['notBlank' => true]);
        // ->addRow('attribute', $attribute->attribute, 'Linked to', 'select', true, $attributes, ['notBlank' => true]);

        if ($attribute->type === 'nationality') {
            $attributesCountries = $this->model->findAll('country');
            $form
                ->addRow('attribute', $attribute->attribute, 'Linked country', 'select', true, $attributesCountries, ['notBlank' => true]);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->update($id, $data);

            if ($response) {
                $this->redirect('attributes');
            }
        }

        $pageTitle = 'Edit an attribute';

        $this->render('attributes/form', compact('pageTitle', 'form', 'attribute'));
    }

    /**
     * Delete an attribute
     * @param int $id - Attribute's Id
     */
    public function delete($id)
    {
        $attribute = $this->model->findById($id);

        if (!$attribute) {
            $this->redirect('attributes');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);
            } else {
                $this->messageManager->setError('Attribute no deleted in database');
            }
            $this->redirect('attributes');
        }


        $pageTitle = 'Delete an attribute';
        $this->render('attributes/delete', compact('pageTitle', 'attribute'));
    }

    private function typeExist($key): bool
    {
        return array_key_exists($key, $this->types);
    }
}
