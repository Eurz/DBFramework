<?php

namespace App\Controller;

use App\Entities\HidingsEntity;
use App\Model\Attributes;
use Core\Controller;
use Core\Forms;
use Core\Http;

class HidingsController extends Controller
{
    private Attributes $Attributes;

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

        $countries = $this->Attributes->findIdAndTitle('country');
        $hidingTypes = $this->Attributes->findIdAndTitle('hiding');

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
                $message = 'Hiding saved in database';

                $id = $this->model->lastInsertId();

                $this->redirect('hidings/edit/' . $id);
            }
        }


        $pageTitle = 'Add an hiding';
        $this->render('hidings/form', compact('pageTitle', 'message', 'form'));
    }

    public function edit($id)
    {
        $message = '';
        $hiding = $this->model->findById($id);
        $countries = $this->Attributes->findIdAndTitle('country');
        $hidingTypes = $this->Attributes->findIdAndTitle('hiding');

        $hiding = $this->model->findById($id);

        if ($hiding === false) {
            $message = 'Cet utilisateur n\'existe pas';
            $this->notFound('attributes');
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

        $this->render('hidings/form', compact('pageTitle', 'form', 'message'));
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
