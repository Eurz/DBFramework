<?php

namespace App\Controller;

use App\Entities\AttributesEntity;
use Core\Forms\Forms;
use Core\Http;
use Core\Session;
use Error;
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

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
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
        if ($filter !== null) {
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
     */
    public function add()
    {
        $types = $this->types;

        $form = new Forms();
        $form
            ->addRow('title', '', 'Title', 'input:text', true, null, ['notBlank' => true])
            ->addRow('type', 'country', 'Type', 'select', true, $types, ['notBlank' => true]);
        var_dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->insert($data);

            if ($response) {

                $id = $this->model->lastInsertId();

                $this->redirect('attributes/edit/' . $id);
            }
        }

        $pageTitle = 'Add an attribute';
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

        if ($attribute === false) {
            $this->redirect('attributes');
        }

        $form = new Forms();
        $form
            ->addRow('title', $attribute->title, 'Title', 'input:text', true, null, ['notBlank' => true])
            ->addRow('type', $attribute->type, 'Type', 'select', true, $types, ['notBlank' => true]);

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
}
