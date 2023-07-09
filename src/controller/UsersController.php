<?php

namespace App\Controller;

use App\Model\Attributes;
use Core\Forms\Forms;

class UsersController extends AppController
{
    private Attributes $Attributes;

    private array $types = ['agent' => 'Agent', 'contact' => 'Contact', 'target' => 'Target', 'manager' => 'Manager'];

    public function __construct()
    {
        parent::__construct();
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
        $nationalities = $this->Attributes->findByKeys('id', 'title', 'nationality');

        $types = $this->types;
        $form = new Forms();

        $form
            ->addRow('firstName', '', 'First name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('lastName', '', 'Last name', 'input:text', true, null, ['notBlank' => true]);

        switch ($userType) {
            case 'agent':
                $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');
                $form
                    ->addRow('identificationCode', '', 'Identification Code', 'input:text', true, null, ['notBlank' => true])
                    ->addRow('nationalityId', '', 'Nationality', 'select', true, $nationalities, ['notBlank' => true])
                    ->addRow('dateOfBirth', '', 'Date of birth', 'input:date', true, null, ['notBlank' => true])
                    ->addRow('specialities', [], 'Specialities', 'select:multiple', true, $specialities, ['notBlank' => true, 'minValue' => 1]);
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
                    ->addRow('password', '', 'Password', 'input:password', true, null, ['notBlank' => true]);

                break;
            default:
                $this->redirect('users');
                break;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($userType === 'manager') {
                $data['password'] = $this->auth->hashPassword($data['password']);
            }

            $response = $this->model->insertUser($data, $userType);
            if ($response) {

                $this->redirect('users/edit/' . $response);
            }
        }

        $pageTitle = 'Add an ';
        $pageTitle .= $userType ? $this->getType($userType) : 'user';
        $this->render('users/form', compact('pageTitle', 'form'));
    }

    /**
     * Edit an user
     * @param int $id - User's Id
     */
    public function edit($id)
    {
        $user = $this->model->findUserById($id);
        $nationalities = $this->Attributes->findByKeys('id', 'title', 'nationality');

        if (!$user) {
            $this->notFound();
        }

        $form = new Forms();

        $form
            ->addRow('firstName', $user->firstName, 'First name', 'input:text', true, null, ['notBlank' => true])
            ->addRow('lastName', $user->lastName, 'Last name', 'input:text', true, null, ['notBlank' => true]);

        switch ($user->userType) {
            case 'agent':
                $specialities = $this->Attributes->findByKeys('id', 'title', 'speciality');
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

            if ($user->userType === 'manager') {
                $data['password'] = $this->auth->hashPassword($data['password']);
            }

            $response = $this->model->updateUser($id, $data);

            if ($response) {
                $this->redirect('users');
            }
        }

        $pageTitle = 'Edit an ';
        $pageTitle .= $user ? $this->getType($user->userType) : 'user';


        $this->render('users/form', compact('pageTitle', 'form', 'user'));
    }

    /**
     * Delete a user
     * @param int $id - User's ID
     */
    public function delete($id)
    {
        $user = $this->model->findById($id);

        if (!$user) {
            $this->redirect('users');
        };

        $form = new Forms();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if (isset($data['choice']) && $data['choice'] === 'yes') {
                $response = $this->model->delete($id);
            }
            $this->redirect('users');
        }

        $pageTitle = 'Delete a user';
        $this->render('users/delete', compact('pageTitle', 'user'));
    }

    public function login()
    {

        $form = new Forms();
        $form->addRow('email', '', 'Email', 'input:email', true, null);
        $form->addRow('password', '', 'Password', 'input:password', true, null);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];
            if ($this->auth->login($email, $password)) {
                $this->redirect('home');
            }
        }

        $pageTitle = 'Login page';
        $this->render('users/form', compact('pageTitle', 'form'));
    }


    public function signIn()
    {

        $form = new Forms();
        $form->addRow('email', '', 'Email', 'input:email', true, null);
        $form->addRow('password', '', 'Password', 'input:password', true, null);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $response = $this->model->insertUser($data);

            if ($response) {

                $this->redirect('users/edit/' . $response);
            }
        }

        $pageTitle = 'Signin page';
        $this->render('users/form', compact('pageTitle', 'message', 'form'));
    }

    public function logout()
    {
        $this->messageManager->setSuccess('You\ve been logout');
        $this->auth->logout();
        $pageTitle = 'Logout page';
        $this->render('users/form', compact('pageTitle'));
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
