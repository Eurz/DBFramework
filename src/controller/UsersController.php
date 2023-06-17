<?php

namespace App\Controller;

use App\Model\Attributes;
use Core\Forms;

class UsersController extends AppController
{
    private Attributes $Attributes;
    private array $types = ['agent' => 'Agent', 'contact' => 'Contact', 'target' => 'Target', 'manager' => 'Manager'];

    public function __construct()
    {
        $this->model = $this->getModel();
        $this->Attributes = $this->getModel('attributes');
    }

    /**
     * Display a user
     * @param int $id - User's Id
     */
    public function view($id)
    {

        $user = $this->model->findUserById($id);
        if (!$user) {
            $this->redirect('/users');
        }

        $pageTitle = $user->fullName;

        $this->render('users/view', compact('pageTitle', 'user'));
    }

    /**
     * Read all users
     */
    public function index()
    {
        $users = $this->model->findAll();
        $pageTitle = 'Users';
        $this->render('users/index', compact('pageTitle', 'users'));
    }

    /**
     * Create an user
     * @param string $userType - Define ype of user to create
     */
    public function add($userType)
    {
        $message = 'sss';
        $nationalities = $this->Attributes->findIdAndTitle('nationality');
        $types = $this->types;
        $form = new Forms();

        $form
            ->addRow('firstName', '', 'First name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('lastName', '', 'Last name', 'input:text', true, null, ['notBlank' => true]);

        switch ($userType) {
            case 'agent':
                $specialities = $this->Attributes->findIdAndTitle('speciality');
                $form
                    ->addRow('identificationCode', '', 'Identification Code', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('nationalityId', '', 'Nationality', 'select', true, $nationalities, ['notBlank' => true])
                    ->addRow('dateOfBirth', '', 'Date of birth', 'input:date', true, null, ['notBlank' => true])
                    ->addRow('specialities', [], 'Specialities', 'select:multiple', true, $specialities, ['notBlank' => true]);
                break;
            case 'target';
            case 'contact':
                $form
                    ->addRow('codeName', '', 'Code name', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('dateOfBirth', '', 'Date of birth', 'input:date', true, null, ['notBlank' => true])
                    ->addRow('nationalityId', '', 'Nationality', 'select', true, $nationalities, ['notBlank' => true]);
                break;
            case 'manager':
                $form
                    ->addRow('email', '', 'Email', 'input:email', true, null, ['notBlank' => true])
                    ->addRow('password', '', 'Password', 'input:text', true, null, ['notBlank' => true]);

                break;
            default:
                $this->redirect('users');
                break;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->insertUser($data, $userType);
            if ($response) {
                $message = 'User saved in database';
                // $id = $this->model->lastInsertId();

                $this->redirect('users/edit/' . $response);
            }
        }

        $pageTitle = 'Add an ';
        $pageTitle .= $userType ? $this->getType($userType) : 'user';
        $this->render('users/form', compact('pageTitle', 'message', 'form'));
    }

    /**
     * Edit an user
     * @param int $id - User's Id
     */
    public function edit($id)
    {
        $message = '';
        $nationalities = $this->Attributes->findIdAndTitle('nationality');
        $user = $this->model->findUserById($id);
        if (!$user) {
            $this->redirect('/users');
        }

        $form = new Forms();

        $form
            ->addRow('firstName', $user->firstName, 'First name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('lastName', $user->lastName, 'Last name', 'input:text', true, null, ['notBlank' => true]);

        switch ($user->userType) {
            case 'agent':
                $specialities = $this->Attributes->findIdAndTitle('speciality');
                $form
                    ->addRow('identificationCode', $user->identificationCode, 'Identification Code', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('nationalityId', $user->nationalityId, 'Nationality', 'select', true, $nationalities, ['notBlank' => true])
                    ->addRow('dateOfBirth', $user->dateOfBirth, 'Date of birth', 'input:date', true, null, ['notBlank' => true])
                    ->addRow('specialities', $user->specialities, 'Specialities', 'select:multiple', true, $specialities, ['notBlank' => true]);
                break;
            case 'target';
            case 'contact':
                $form
                    ->addRow('codeName', $user->codeName, 'Code name', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('dateOfBirth', $user->dateOfBirth, 'Date of birth', 'input:date', true, null, ['notBlank' => true])
                    ->addRow('nationalityId', $user->nationalityId, 'Nationality', 'select', true, $nationalities, ['notBlank' => true]);
                break;
            case 'manager':
                $form
                    ->addRow('email', $user->email, 'Email', 'input:email', true, null, ['notBlank' => true])
                    ->addRow('password', $user->password, 'Password', 'input:password', true, null, ['notBlank' => true]);

                break;
            default:
                break;
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->updateUser($id, $data);

            if ($response) {
                $message = 'User saved in database';

                $this->redirect('users/edit/' . $response);
            }
        }

        $pageTitle = 'Edit an ';
        $pageTitle .= $user ? $this->getType($user->userType) : 'user';


        $this->render('users/form', compact('pageTitle', 'message', 'form', 'user'));
    }

    /**
     * Delete a user
     * @param int $id - User's ID
     */
    public function delete($id)
    {
        $message = '';
        $user = $this->model->findById($id);

        if (!$user) {
            $message = 'This user doesn\'t exist';
            $this->redirect('users');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);

                if ($response) {
                    $message = 'User deleted in database';
                    $this->redirect('users');
                }
            } else {
                $message = 'User deleted in database';
                $this->redirect('users');
            }
        }

        $pageTitle = 'Delete a user';
        $this->render('users/delete', compact('pageTitle', 'user', 'message'));
    }

    /**
     * Returns allowed types of user
     * @param string $key - Name of type
     */
    private function getType($key): string
    {

        return $this->types[$key];
    }
}
