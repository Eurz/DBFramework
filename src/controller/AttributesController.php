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
                    $message = 'Field "' . $key . '"is required';
                    $isValid = false;
                    break;
                } else {
                    $isValid = true;
                }

                $data[$key] = $value;
            }

            if ($isValid !== false) {
                $response = $this->model->insert($data);
                if ($response) {
                    $message = 'Attribute saved in database';


                    //  Http::redirect('attributes/edit/' . $id);


                }
            }
        }

        $pageTitle = 'Add an attribute';
        $this->render('attribute/add', compact('pageTitle', 'data', 'message', 'types'));
    }

    /**
     * Edit an attribute
     */
    public function edit($id)
    {
        $message = '';
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
    }
}
