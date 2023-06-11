<?php

namespace App\Controller;

use App\Entities\HidingsEntity;
use Core\Controller;
use Core\Http;

class HidingsController extends Controller
{
    private $Attributes;

    public function __construct()
    {
        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('Attributes');
    }

    /**
     * Read all attributes
     */
    public function index()
    {
        $hidings = $this->model->findAll();

        $pageTitle = 'Hidings';
        $this->render('hidings/index', compact('pageTitle', 'hidings'));
    }

    function add()
    {
        $message = 'Test ajout hiding';
        $hiding = new HidingsEntity();

        $countries = $this->Attributes->findAll('country');
        $hidingTypes = $this->Attributes->findAll('hiding');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
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
                    $message = 'Hiding saved in database';
                    $id = $this->model->lastInsertId();

                    Http::redirect('hidings/edit/' . $id);
                }

                echo ' Formulaire valid';
            } else {
                echo 'Formulaire non valide';
            }
        }

        $pageTitle = 'Add an hiding';
        $this->render('hidings/form', compact('pageTitle', 'hiding', 'countries', 'hidingTypes', 'message'));
    }

    public function edit($id)
    {
        $message = null;
        $hiding = $this->model->findById($id);
        $countries = $this->Attributes->findAll('country');
        $hidingTypes = $this->Attributes->findAll('hiding');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
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
                    $message = 'Hiding saved in database';
                    // $id = $this->model->lastInsertId();

                    Http::redirect('hidings/edit/' . $id);
                }
            }
        }

        $pageTitle = 'Edit an hiding';
        $this->render('hidings/form', compact('pageTitle', 'hiding', 'countries', 'hidingTypes', 'message'));
    }
}
