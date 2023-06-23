<?php

namespace Core;

class Session
{
    private $data = [];
    /** 
     * @var string - Key of data in SESSION
     */
    private $sessionName;


    /**
     * @var Session - Key of $_SESSION
     */
    private $_instance;

    public function __construct()
    {
        $this->data = $_SESSION;
    }

    /**
     * @param string $key - Key of data in $_SESSION
     * @param mixed $data - Data attributed to the key
     * @param mixed $type - Key in sub array for current $key
     */
    public function set($key, $data, $type = null): void
    {

        if ($type) {
            $_SESSION[$key][$type] = $data;
            return;
        }

        $_SESSION[$key] = $data;
    }

    /**
     * Get data from $_SESSION[$key]
     * @param string $key - Key in $ession
     * @return mixed
     **/
    public function get(string $key): mixed
    {
        if ($this->exist($key)) {
            $message = $_SESSION[$key];
            return $message;
        }
        return null;
    }

    /**
     * @param string $key - Key in $_SESSION
     * @return bool - True if key existe otherwise false
     */
    public function exist($key): bool
    {
        // var_dump(isset($_SESSION[$key]));
        // return array_key_exists($key, $_SESSION);
        return isset($_SESSION[$key]);
    }

    /**
     * Delete a value in SESSION
     * @param string $key - Key of the value in $_SESSION
     * return void
     */
    public function delete($key): void
    {
        if ($this->exist($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Push data in SESSION[key]
     * @param array $data - Data to add to SESSION[key]
     */
    public function merge($data)
    {

        if ($this->exist($this->sessionName)) {
            $result = array_merge($this->get($this->sessionName), $data);
            $this->set($this->sessionName, $result);
        }
    }

    /**
     * Delete all sessions
     */
    public function reset()
    {
        session_destroy();
    }

    /**
     * Get value $_SESSION[$key]
     * @param mixed $key
     */

    public function getValue($key)
    {
        return $this->get($this->sessionName)[$key];
    }


    /**
     * @return Session - Current instance of Session
     */
    public function getInstance()
    {
        if ($this->_instance === null) {
            $this->_instance = new Session();
        }

        // $this->_instance->set('session', $id);
        return $this->_instance;
    }
}
