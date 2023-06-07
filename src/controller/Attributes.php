<?php

namespace App\Controller;

use Core\Database;
use Core\Http;

class Attributes extends DefaultController
{
    private array $types = ['country' => 'Country', 'nationality' => 'Nationalities', 'hiding' => 'Hidings type', 'speciality' => 'Specialities', 'status' => 'Missions status', 'type' => 'Missions types', 'userType' => 'User type'];


    public function index()
    {

        $attributes = $this->model->findAll();
        $hidings = $this->getModel('hidings')->findAll();

        $types = $this->types;

        $pageTitle = 'Welcome to SPION';
        $this->render('attribute/index', compact('pageTitle', 'attributes', 'types'));
    }

    public function view($id)
    {
        $attribute = $this->model->findById($id);
        // if (!$attribute) {
        //     $attribute = [];
        // }

        $pageTitle = 'View an attribute';
        $this->render('attribute/view', compact('pageTitle', 'attribute'));
    }


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

    public function edit($id)
    {
        $attribute = $this->model->findById($id);
        var_dump($attribute);
        $pageTitle = 'Edit an attribute';

        $this->render('attribute/form', compact('pageTitle', 'attribute'));
    }
}
