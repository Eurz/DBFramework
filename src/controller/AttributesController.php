<?php

namespace App\Controller;

use App\Entities\AttributesEntity;
use Core\Http;

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

        $attribute = new AttributesEntity();
        $attribute->setTitle($_POST['title'] ?? '');
        $attribute->setType($_POST['type'] ?? 'country');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $this->model->insert($_POST);

            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    $message = 'Field "' . $key . '" is required';
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
        $isValid = false;

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
                    $message = 'Field "' . $key . '" is required';
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


        $pageTitle = 'Edit an attribute';

        $this->render('attribute/form', compact('pageTitle', 'attribute', 'message', 'types'));
    }

    /**
     * 
     */
    public function delete($id)
    {

        // CHECK if ATTRIBUTE with $id exist
        // If ATTRIBUTE exist
        //      DELETE ATTRIBUTE
        // ELSE
        // CREATE error message
        // REDIRECT to all attributes

        $attribute = $this->model->findById($id);
        $message = '';
        $isValid = false;

        if (!$attribute) {
            $message = 'This attribute doesn\'t exist';
        };

        // if($attribute)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['choice']) && $_POST['choice'] === 'yes') {
                $isValid = true;
            }



            if ($isValid !== false) {
                $response = $this->model->delete($id);

                if ($response) {
                    $message = 'Attribute delete in database';
                }
            }

            Http::redirect('attributes');
        }


        $pageTitle = 'Delete an attribute';
        $this->render('attribute/delete', compact('pageTitle', 'attribute', 'message'));
    }
}
