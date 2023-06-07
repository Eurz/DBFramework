<?php

namespace App\Controller;

use Core\Database;
use Core\Http;

class AttributesController extends DefaultController
{
    private array $types = ['country' => 'Country', 'nationality' => 'Nationalities', 'hiding' => 'Hidings type', 'speciality' => 'Specialities', 'status' => 'Missions status', 'type' => 'Missions types', 'userType' => 'User type'];

    /**
     * Read all attributes
     */
    public function index()
    {
        $attributes = $this->model->findAll();
        $hidings = $this->getModel('hidings')->findAll();

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
    public function create()
    {
        $response = $this->model->insert(['title' => 'salutjinsere', 'type' => 'typetest']);
        if (!$response) {
            echo 'Echec de linsertion';
        } else {
            Http::redirect('/attribute');
        }

        $pageTitle = 'Add an attribute';
        $this->render('attribute/form', compact('pageTitle'));
    }

    /**
     * Edit an attribute
     */
    public function edit($id)
    {
        $attribute = $this->model->findById($id);
        var_dump($attribute);
        $pageTitle = 'Edit an attribute';

        $this->render('attribute/form', compact('pageTitle', 'attribute'));
    }
}
