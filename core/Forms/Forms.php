<?php

namespace Core\Forms;

use Core\Messages;

class Forms extends AsbstractForms
{

    /**
     * Final data to extract
     */
    protected $data = [];



    public function __construct()
    {
        $this->data = $_POST;
    }

    private $isValid = false;


    // Specific to form submission
    /**
     * Form validation
     * @return bool
     */
    public function isValid(): bool
    {
        var_dump($_POST);
        $result = [];
        foreach ($this->data as $key => $value) {
            if ($value === '' || $value === null) {
                $result[$key] = 'failed';
                $this->message[] = 'Field "' . $key . '" is required';
            } else {
                $result[$key] = 'success';
                $this->isValid = true;
            }

            if (isset($this->message)) {
                $messageManager = new Messages();
                $messageManager->setError(implode('<br/>', $this->message));
            }
        }
        return !in_array('failed', $result);

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

        return false;
    }
}
