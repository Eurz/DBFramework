<?php

namespace App\Controller;

use App\Entities\AttributesEntity;
use Core\Forms;
use Core\Http;
use Error;

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
        $this->model = $this->getModel();
    }

    /**
     * Read all attributes
     */
    public function index()
    {
        $attributes = $this->model->findAll();

        $types = $this->types;
        $pageTitle = 'Missions attributes';
        $this->render('attributes/index', compact('pageTitle', 'attributes', 'types'));
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
        $message = '';
        $types = $this->types;
        $isValid = false;

        $form = new Forms();
        $form
            ->addRow('title', '', 'Title', 'input:text', true, null, ['notBlank' => true])
            ->addRow('type', 'country', 'Type', 'select', true, $types, ['notBlank' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->insert($data);

            if ($response) {
                $message = 'Attribute saved in database';

                $id = $this->model->lastInsertId();

                $this->redirect('attributes/edit/' . $id);
            }
        }

        $pageTitle = 'Add an attribute';
        $this->render('attributes/form', compact('pageTitle', 'message', 'types', 'form'));
    }

    /**
     * Edit an attribute
     * @param int $id - Attribute's ID
     */
    public function edit($id)
    {
        $message = '';
        $types = $this->types;
        $attribute = $this->model->findById($id);

        if ($attribute === false) {
            $message = 'Cet utilisateur n\'existe pas';
            $this->notFound('attributes');
        }

        $form = new Forms();
        $form
            ->addRow('title', $attribute->title, 'Title', 'input:text', true, null, ['notBlank' => true])
            ->addRow('type', $attribute->type, 'Type', 'select', true, $types, ['notBlank' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->update($id, $_POST);

            if ($response) {
                $this->redirect('attributes/edit/' . $id);
            }
        }

        $pageTitle = 'Edit an attribute';

        $this->render('attributes/form', compact('pageTitle', 'form', 'message'));
    }

    /**
     * Delete an attribute
     * @param int $id - Attribute's Id
     */
    public function delete($id)
    {
        $message = '';
        $attribute = $this->model->findById($id);

        if (!$attribute) {
            $message = 'This attribute doesn\'t exist';
            $this->redirect('attributes');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);

                if ($response) {
                    $message = 'Attribute deleted in database';
                    $this->redirect('attributes');
                }
            } else {
                $message = 'Attribute deleted in database';
                $this->redirect('attributes');
            }
        }


        $pageTitle = 'Delete an attribute';
        $this->render('attributes/delete', compact('pageTitle', 'attribute', 'message'));
    }
}
