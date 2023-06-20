<?php

namespace Core;

use App\Model\Users;

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
            }
            return true;
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

    public function logout()
    {
        if ($this->isLogged()) {
            unset($_SESSION['user']);
        }
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }


    public function getUserId()
    {
        if ($this->isLogged()) {
            return $_SESSION['user'];
        }
        return;
    }
}
