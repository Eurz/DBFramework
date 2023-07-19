<?php

namespace Core;


class Authentication
{

    private Database $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function login($email, $password)
    {

        $query = "SELECT id,email, password" . SPACER;
        $query .= "FROM users" . SPACER;
        $query .= "WHERE email = :email";
        $user = $this->db->query($query, ['email' => $email], null, true);

        if ($user) {

            $result = $this->verifyPassword($password, $user['password']);

            if ($result) {
                $_SESSION['user'] = $user['id'];
                return true;
            }
        }

        return false;
    }


    /**
     * Check if password of user is valid
     * @param string $password - User password from login form
     * @param string $userPassword - Current user's password
     */
    private function verifyPassword($password, $userPassword)
    {
        return password_verify($password, $userPassword);
    }


    public function signIn()
    {
    }
    /**
     * Log out current user. Delete user key in current SESSION
     * @return void
     */
    public function logout(): void
    {
        if ($this->isLogged()) {
            unset($_SESSION['user']);
        }
    }

    /**
     * Check if user is authenticated
     * @return bool
     */
    public function isLogged(): bool
    {
        if (isset($_SESSION['user'])) {
            $query = "SELECT * FROM users WHERE id = :id" . SPACER;
            $user = $this->db->query($query, [':id' => $_SESSION['user']], null, true);
            if (!$user) {;
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Get user's id authenticated
     */
    public function getUserId()
    {
        if ($this->isLogged()) {
            return $_SESSION['user'];
        }

        return null;
    }

    /**
     * Get the authenticated user
     * @return $user
     */
    public function getUser(): Entity|bool
    {
        $usersModel = Application::getInstance()->getModel('users');
        $user = $usersModel->findUserById($this->getUserId());

        return $user;
    }

    /**
     * Checks if a user has the required role
     * @param int|string $role - Role to check
     * @return bool - True if role is checked otherwise false
     */
    public function grantedAccess($role)
    {
        if ($this->getUser() !== false) {
            return in_array($role, $this->getUser()->getRoles());
        }

        return false;
    }
}
