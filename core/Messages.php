<?php

namespace Core;

class Messages
{
    private Session $sessionManager;
    private string $sessionName = 'messages';
    protected $data;

    public function __construct()
    {
        $this->sessionManager = new Session();
        $this->data = $this->sessionManager->getInstance()->get($this->sessionName);
    }

    /**
     * Set messages in session with "success" or "error" type
     * @param string $message - Message to display for the defined type
     * @param string $type
     */
    private function set(string $message, string $type = 'success')
    {
        $this->sessionManager->getInstance()->set($this->sessionName, $message, $type);
    }

    public function setSuccess($message)
    {
        $this->set($message, 'success');
    }

    public function setError($message)
    {
        $this->set($message, 'error');
    }

    /**
     * Read messages and return html
     * @return ?string $html 
     */
    public function read(): ?string
    {
        $messages = $this->data;
        $html = null;
        if ($messages) {
            if (isset($messages['error'])) {
                $html = '<div class="alert alert-danger">' . $messages['error'] . '</div';
            } elseif (isset($messages['success'])) {
                $html = '<div class="alert alert-success">' . $messages['success'] . '</div>';
            }
        }
        $this->sessionManager->getInstance()->delete($this->sessionName);
        return $html;
    }

    /**
     * Delete all messages
     */
    public function delete()
    {

        $this->sessionManager->getInstance()->delete($this->sessionName);
    }
}
