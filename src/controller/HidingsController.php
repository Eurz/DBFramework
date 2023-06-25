<?php

namespace App\Controller;

use App\Model\Attributes;
use Core\Forms;

class HidingsController extends AppController
{
    private Attributes $Attributes;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('Attributes');
    }

    /**
     * Read all attributes
     */
    public function index()
    {
        var_dump($_SESSION);
        $field = filter_input(INPUT_GET, 'field', FILTER_DEFAULT);
        $orderBy = filter_input(INPUT_GET, 'orderBy', FILTER_DEFAULT);
        $filterByCountry = filter_input(INPUT_GET, 'filterByCountry', FILTER_VALIDATE_INT);

        $hidings = $this->model->findWithFilters($field, $filterByCountry, $orderBy);
        $hidingsTypes = $this->Attributes->findAll('hiding');
        $countries = $this->Attributes->findAll('country');

        $pageTitle = 'Hidings';
        $this->render('hidings/index', compact('pageTitle', 'hidings', 'countries', 'hidingsTypes'));
    }



    function add()
    {
        $pageTitle = 'Add an hiding';

        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $hidingTypes = $this->Attributes->findByKeys('id', 'title', 'hiding');
        $form = null;

        if (!$countries || !$hidingTypes) {
            $this->messageManager->setError('Firstly, you must create attributes country and hiding', 'error');

            $this->render('hidings/actions', compact('pageTitle', 'message'));
        } else {
            $form = new Forms();
            $form
                ->addRow('code', '', 'Code', 'input:text', true, null, ['notBlank' => true])
                ->addRow('address', '', 'Address', 'input:text', true, null, ['notBlank' => true])
                ->addRow('typeId', '', 'Type', 'select', true, $hidingTypes, ['notBlank' => true])
                ->addRow('countryId', '', 'Country', 'select', true, $countries, ['notBlank' => true]);

            if ($form->isSubmitted() && $form->isValid()) {
                $hiding = $form->getData();
                $response = $this->model->insert($hiding);

                if ($response) {
                    $id = $this->model->lastInsertId();

                    $this->redirect('hidings/edit/' . $id);
                }
            }
            $this->render('hidings/form', compact('pageTitle', 'form'));
        }
    }

    public function edit($id)
    {
        $hiding = $this->model->findById($id);
        $countries = $this->Attributes->findByKeys('id', 'title', 'country');
        $hidingTypes = $this->Attributes->findByKeys('id', 'title', 'hiding');

        $hiding = $this->model->findById($id);

        if ($hiding === false) {
            $this->redirect('hidings');
        }

        $form = new Forms();
        $form
            ->addRow('code', $hiding->code, 'Code', 'input:text', true, null, ['notBlank' => true])
            ->addRow('address', $hiding->address, 'Address', 'input:text', true, null, ['notBlank' => true])
            ->addRow('typeId', $hiding->typeId, 'Type', 'select', true, $hidingTypes, ['notBlank' => true])
            ->addRow('countryId', $hiding->countryId, 'Country', 'select', true, $countries, ['notBlank' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->update($id, $data);

            if ($response) {
                $this->redirect('hidings/edit/' . $id);
            }
        }

        $pageTitle = 'Edit an hiding';

        $this->render('hidings/form', compact('pageTitle', 'form'));
    }

    public function delete($id)
    {

        $message = '';
        $hiding = $this->model->findById($id);
        if (!$hiding) {
            $message = 'This hiding doesn\'t exist';
            $this->redirect('hidings');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);

                if ($response) {
                    $message = 'Hiding deleted in database';
                    $this->redirect('hidings');
                }
            } else {
                $message = 'Hiding deleted in database';
                $this->redirect('hidings');
            }
        }

        $pageTitle = 'Delete an hiding';
        $this->render('hidings/delete', compact('pageTitle', 'hiding', 'message'));
    }
}
