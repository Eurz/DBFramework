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

        // $args = array(
        //     'firstName'   => FILTER_SANITIZE_ENCODED,
        //     'lastName'   => FILTER_SANITIZE_ENCODED,
        //     'identificationCode' => FILTER_SANITIZE_ENCODED,
        //     'nationalityId' =>  array(
        //         'filter' => FILTER_VALIDATE_INT,
        //         // 'options' => array("regexp" => "#^([0-9]+)$#")
        //     ),
        //     'dateOfBirth' => array(
        //         'filter' => FILTER_VALIDATE_REGEXP,
        //         'options' => array("regexp" => "#^(0?[1-9])-([\d]{4})#")
        //     ),
        //     'specialities'    => array(
        //         'filter' => FILTER_VALIDATE_INT,
        //         'flags'  => FILTER_REQUIRE_ARRAY,
        //     )

        // );

        // $myinputs = filter_input_array(INPUT_POST, $args);
        $result = [];

        foreach ($this->formParams as $key => $value) {
            $params = $value['params'];
            foreach ($params as $paramKey => $paramValue) {
                // $this->$key($value);
                $result[$key] = call_user_func(array($this, $paramKey), $key, $paramValue);
            }
        }
        if (isset($this->message)) {
            $messageManager = new Messages();
            $messageManager->setError(implode('<br/>', $this->message));
        }

        // foreach ($this->data as $key => $value) {

        //     if ($this->checkData($value) === false) {

        //         $result[$key] = 'failed';
        //         // $this->message[] = 'Field "' . $key . '" is required';
        //     } else {
        //         $result[$key] = 'success';
        //         $this->isValid = true;
        //     }

        //     if (isset($this->message)) {
        //         $messageManager = new Messages();
        //         $messageManager->setError(implode('<br/>', $this->message));
        //     }
        // }
        // return !in_array('failed', $result);
        return !in_array(false, $result);
    }


    private function checkData($value): bool
    {

        if (is_array($value) && count($value) === 0) {
            return false;
        }
        if ($value === '' || $value === null) {
            return false;
        }

        return true;
    }

    /**
     * Check if $inputKey is not empty or not null, depending on if $inputParam is true
     * @param string $inputKey - Key of input to check in form
     * @param bool $inputParam - True if wanted not to be empty otherwise false
     * @return bool
     */
    private function notBlank(string $inputKey, bool $inputParam): bool
    {
        if ($inputParam) {

            $input = filter_input(INPUT_POST, $inputKey, FILTER_DEFAULT);
            if ($input === '') {
                $this->message[$inputKey] = 'Field "' . $inputKey . '" is required';
                return false;
            }
        }
        return true;
    }

    /**
     * Check min required values for a form-select
     * @param string $inputKey - Key of select to check in form
     * @param int $inputParam - Min value for form-select value
     * @return bool
     */
    private function minValue(string $inputKey, int $inputParam): bool
    {

        $input = filter_input(
            INPUT_POST,
            $inputKey,
            FILTER_VALIDATE_INT,
            array(
                'flags'  => FILTER_REQUIRE_ARRAY
            )
        );

        if ($inputParam > 0) {
            if ($input === null) {
                $this->message[$inputKey] = 'Choose at least ' . $inputParam . ' "' . $inputKey . '"';
                return false;
            } elseif (count($input) < $inputParam) {
                $this->message[$inputKey] = 'Champs ' . $inputKey . ' requiert au moins ' . $inputParam . ' item';
                return false;
            }
        }
        return true;

        // if (count($input) < $inputParam) {
        //     $this->message[] = 'Champs ' . $inputKey . ' requiert au moins ' . $inputParam . ' item';
        // }
        // $this->message[] = 'Champs ' . $inputKey . ' contient au moins ' . $inputParam . 'item';
    }
}
