<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Entities\AttributesEntity;
=======
use App\Entity\AttributeEntity;
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
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
<<<<<<< HEAD

    public function __construct()
    {
        $this->model = $this->getModel();
    }
=======
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b

    /**
     * Read all attributes
     */
    public function index()
    {
        $attributes = $this->model->findAll();
<<<<<<< HEAD

=======
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b

        $types = $this->types;
        $pageTitle = 'Welcome to SPION';
        // var_dump($attributes);
        // $this->render('attribute/index', compact('pageTitle', 'attributes', 'types'));
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
<<<<<<< HEAD

        $types = $this->types;
        $isValid = false;

        $attribute = new AttributesEntity();
        $attribute->setTitle($_POST['title'] ?? '');
        $attribute->setType($_POST['type'] ?? 'country');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                if ($value === '') {
=======
        $attribute = new \App\Entities\AttributeEntity();
        $attribute->setCreatedAt(date("Y-m-d H:i:s"));

        $types = $this->types;
        $isValid = false;
        $data = ['title' => '', 'type' => 'country'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $data = $_POST;
            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    // $attribute->setTitle($_POST['title']);
                    // $attribute->setType($_POST['type']);
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
                    $message = 'Field "' . $key . '"is required';
                    $isValid = false;
                    break;
                } else {
                    $isValid = true;
                }

                $data[$key] = $value;
            }

            if ($isValid !== false) {
<<<<<<< HEAD
                $response = $this->model->insert($_POST);

                if ($response) {
                    $message = 'Attribute saved in database';
                    // $id = $this->model->lastInsertId();

                    // Http::redirect('attributes/edit/' . $id);
                    Http::redirect('attributes');
=======
                $response = $this->model->insert($data);
                if ($response) {
                    $message = 'Attribute saved in database';


                    //  Http::redirect('attributes/edit/' . $id);


>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
                }
            }
        }

        $pageTitle = 'Add an attribute';
<<<<<<< HEAD
        $this->render('attribute/form', compact('pageTitle', 'attribute', 'message', 'types'));
=======
        $this->render('attribute/add', compact('pageTitle', 'data', 'message', 'types'));
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
    }

    /**
     * Edit an attribute
     */
    public function edit($id)
    {
        $message = '';
<<<<<<< HEAD
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
=======
        $attribute = $this->model->findById($id);
        // Si utilisateur n'existe pas, redirection avec message d'erreur (en session)
        if ($attribute === false) {
            $attribute['title'] = 'unknown';
            $message = 'Cet utilisateur n\'existe pas';
            // Http::redirect('attributes');
            // die();
        }

        // Si formulaire soumis
        // => récupération des données
        if (isset($_POST['title'])) {
            $attribute['title'] = $_POST['title'];
            $response = $this->model->update($id, $_POST);

            // Si mise à jour réussie
            if ($response) {
                // => message de succès
                // Http::redirect('attributes/edit/' . $id, '404');
                $message = 'Utilisateur mis à jour';
            } else {
                // => message d'erreur'
                $message = 'Utilisateur non mis à jour';
            }
        }
        $attributeType =  $this->types[$attribute->type] ?? 'attribute';
        $pageTitle = 'Edit an ' . $attributeType;

        $this->render('attribute/edit', compact('pageTitle', 'attribute', 'message'));
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
    }
}
