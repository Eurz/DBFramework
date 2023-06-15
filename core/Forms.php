<?php

namespace Core;

class Forms
{
    /**
     * Parameters to create form
     */
    private $formParams;

    /**
     * Final data to extract
     */
    private $data = [];
    /**
     * Message from form submission
     */
    private $message;

    public function __construct()
    {
        $this->data = $_POST;
    }

    private $isValid = false;

    /**
     * Generate html for an form input element
     * @param string $name Name for input's name attribute
     * @param $value Value for input's value attribute
     * @param string $type Input's type
     * @param string Input's label
     * @return string $html
     */
    public function addInput($name, $type)
    {
        $params = $this->formParams[$name];
        $html = '<div class="row mb-3">';
        $html .= $this->createLabel($name);
        $html .= '<div class="col-lg-6 col-sm-10 ">
        <input class="form-control" type="' . $type . '" name="' . $name . '" id="' . $name . '" value="' . $this->getValue($name) . '" />
        </div>';
        $html .= '</div>';
        return $html;
    }


    /**
     * Generate html for an form select element
     * @param string $name Name for input's name attribute
     * @param $value Value for input's value attribute
     * @param string $type Input's type
     * @param string Input's label
     */
    public function addSelect($name, $type, $data): string
    {
        $params = $this->formParams[$name];

        $html = '<div class="row mb-3">';
        $html .= '<label for="' . $name . '" class="col-sm-2 form-label">' . $params['label'] . '</label>';
        $html .= '<div class="col-lg-6 col-sm-10 ">';

        if ($type === 'multiple') {
            $ext = '[]';
            $typeSize = 'size="' . count($data) . '"';
        } else {
            $ext = null;
            $typeSize = null;
        }

        $html .= '<select name="' . $name . $ext . '"  id="' . $name . '" class="form-select form-select-sm" aria-label="Hiding type" ' . $type . ' ' . $typeSize . '>';
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $keys = array_keys($v);

                $k = $v[$keys[0]];
                $v = $v[$keys[1]];
            }
            if ($type) {
                $selected = in_array($k, $this->getValue($name)) ? 'selected' : null;
            } else {
                $selected = $this->getValue($name) == $k ? 'selected' : null;
            }
            $html .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }
        $html .= '</select>';
        $html .= '</div></div>';
        return $html;
    }


    public function errors()
    {
        if (isset($this->message)) {

            return implode('<br/>', $this->message);
        }

        return false;
    }
    public function extractSelectData(...$keys)
    {
        $data = [];
        foreach ($this->getData() as  $key => $value) {
            if (in_array($key, $keys)) {
                $data[$key] = $this->getValue($key);
            } else {

                $data['default'][$key] = $this->getValue($key);
            }
        }
        return $data;
    }
    /**
     * Define a form element with input parameters
     */
    public function addRow($name, $value, $label, $type = 'text', $required = false, $data = null, $params = [])
    {

        $this->formParams[$name] = compact('name', 'value', 'label', 'type', 'required', 'data', 'params');

        return $this;
    }

    /**
     * Display an form html element
     * @return string $html
     */
    public function row($key)
    {

        if (!$this->has($key)) {
            return null;
        }

        $rowType = $this->formParams[$key]['type'];

        preg_match('#^(select|input):?([a-z)]+)?#', $rowType, $matches);
        array_shift($matches);

        $inputType = $matches[0];
        $type = isset($matches[1]) ? $matches[1] : null;

        $methodName = 'add' . ucfirst($inputType);
        // call_user_func(array($this, $methodName), [$key]);
        $input = $this->$methodName($key, $type, $this->formParams[$key]['data']);
        return $input;
    }



    /**
     * Render the global html of the form
     * @return string 
     */
    public function render()
    {
        // $html = '<form method="POST">';
        $html = '';
        foreach ($this->formParams as $key => $value) {
            $html .= $this->row($key);
        }
        // $html .= '</form>';
        return $html;
    }

    /**
     * Check is form is submitted
     * @return bool True if method is POST otherwise false
     */
    public function isSubmitted(): bool
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->data = $_POST;
            return true;
        }
        return false;
    }

    /**
     * 
     */
    public function isValid(): bool
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            if ($value === '' || $value === null) {
                $result[$key] = 'failed';
                $this->message[] = 'Field "' . $key . '" is required';
            } else {
                $result[$key] = 'success';
                $this->isValid = true;
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

    /**
     * Get the current data of the form
     * @return array 
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Return label of html element
     */

    private function createLabel($key): string
    {
        $label = $this->formParams[$key]['label'];

        return  '<label for="' . $key . '" class="col-sm-2 form-label">' . $label . '</label>';
    }




    /**
     * Get the value of an input element
     */
    private function getValue($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return $this->formParams[$key]['value'];
    }

    private function has($key): bool
    {
        if (array_key_exists($key, $this->formParams)) {
            return true;
        }
        return false;
    }
}
