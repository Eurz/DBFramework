<?php

namespace App\Controller;

use App\Entity\AttributeEntity;
use Core\Http;

class AttributesController extends DefaultController
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

    /**
     * Read all attributes
     */
    public function index()
    {
        $attributes = $this->model->findAll();

        $types = $this->types;
        $pageTitle = 'Welcome to SPION';
        $this->render('attribute/index', compact('pageTitle', 'attributes', 'types'));
    }

    /**
     * Read a single attribute
     */
    public function view($id)
    {
        $attribute = $this->model->findById($id);


        $pageTitle = 'View an attribute';
        $this->render('attribute/view', compact('pageTitle', 'attribute'));
    }

    /**
     * Create an attribute
     */
    public function add()
    {
        $message = '';

        $types = $this->types;
        $isValid = false;

        $attribute = new \App\Entities\AttributeEntity();
        $attribute->setTitle($_POST['title'] ?? '');
        $attribute->setType($_POST['type'] ?? 'country');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    $message = 'Field "' . $key . '"is required';
                    $isValid = false;
                    break;
                } else {
                    $isValid = true;
                }

                $data[$key] = $value;
            }

            if ($isValid !== false) {
                $response = $this->model->insert($_POST);

                if ($response) {
                    $message = 'Attribute saved in database';
                    $id = $this->model->lastInsertId();

                    Http::redirect('attributes/edit/' . $id);
                }
            }
        }

        $pageTitle = 'Add an attribute';
        $this->render('attribute/form', compact('pageTitle', 'attribute', 'message', 'types'));
    }

    /**
     * Edit an attribute
     */
    public function edit($id)
    {
        $message = '';
        $types = $this->types;
        $attribute = $this->model->findById($id);
        // Si utilisateur n'existe pas, redirection avec message d'erreur (en session)
        if ($attribute === false) {
            $message = 'Cet utilisateur n\'existe pas';
            Http::redirect('attributes');
        }

        // Todo: implement a Form Class to manage the form and submitted data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $data = $_POST;
            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    $message = 'Field "' . $key . '"is required';
                    $isValid = false;
                    break;
                } else {
                    $isValid = true;
                }

                $data[$key] = $value;
            }

            if ($isValid !== false) {
                $response = $this->model->update($id, $_POST);


                if ($response) {
                    $message = 'Attribute saved in database';
                    // $id = $this->model->lastInsertId();
                    $message = 'Utilisateur mis à jour';

                    Http::redirect('attributes/edit/' . $id);
                }

                $message = 'Utilisateur non mis à jour';
            }
        }


        $attributeType =  $this->types[$attribute->type] ?? 'attribute';
        $pageTitle = 'Edit an ' . $attributeType;

        $this->render('attribute/form', compact('pageTitle', 'attribute', 'message', 'types'));
    }
}
