<?php

namespace App;

class Session
{
    private $data;
    private $sessionName;

    public function __construct(string $sessionName)
    {
        $this->sessionName = $sessionName;
        if (!$this->exist($sessionName)) {
            $this->set($sessionName, []);
        }
        $this->data = isset($_SESSION) ? $_SESSION : [];
    }


    public function get($key)
    {
        if ($this->exist($this->sessionName)) {
            var_dump($this->sessionName . ' exists');
            return $_SESSION[$this->sessionName];
        }
        return;
    }

    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
        if ($this->exist($key)) {
            // var_dump('exist');
            return;
        }
        // var_dump('No exist');
    }

    public function exist($key): bool
    {
        // var_dump(isset($_SESSION[$key]));
        // return array_key_exists($key, $_SESSION);
        return isset($_SESSION[$key]);
    }

    public function delete($key)
    {
        if ($this->exist($key)) {
            unset($_SESSION[$key]);
        }
    }
    public function merge($data)
    {

        if ($this->exist($this->sessionName)) {

            // var_dump($this->get($this->sessionName));
            // var_dump($data);
            $test = array_merge($this->get($this->sessionName), $data);
            var_dump($test);
            $this->set($this->sessionName, $test);
        }
    }
    public function reset()
    {
        unset($_SESSION);
    }

    /* Methods for inheritance */
}
