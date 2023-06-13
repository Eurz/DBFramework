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
     * Html of the form
     */
    private $errors;

    public function __construct()
    {
        $this->data = $_POST;
    }

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
        $html .= '<label for="' . $name . '" class="col-sm-2 form-label">' . $params['label'] . '</label>';
        $html .= '<div class="col-lg-6 col-sm-10 ">
        <input class="form-control" type="' . $type . '" name="' . $params['type'] . '" id="' . $name . '" value="' . $this->getValue($name) . '" />
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
    public function addSelect($name, $type): string
    {
        $params = $this->formParams[$name];
        $data = $params['data'];
        $html = '<div class="row mb-3">';
        $html .= '<label for="' . $name . '" class="col-sm-2 form-label">' . $params['label'] . '</label>';
        $html .= '<div class="col-lg-6 col-sm-10 ">';

        if ($type === 'multiple') {
            $ext = '[]';
        } else {
            $ext = null;
        }


        $html .= '<select name="' . $name . $ext . '"  id="' . $name . '" class="form-select form-select-sm" aria-label="Hiding type" ' . $type . ' ' . $type ?? 'size="4"' . '>';

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $result = [];
                $test = array_keys($v);

                $k = $v[$test[0]];
                $v = $v[$test[1]];
            }
            if ($type) {
                // var_dump($this->data[$k]);
                var_dump($this->getValue('country'));
                // $selected = isset($this->getValue('country')) && in_array($k, $this->getValue('country')) ? 'selected' : null;
                $selected = null;
            } else {
                $selected = $this->getValue($name) === $k ? 'selected' : null;
            }
            $html .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }
        $html .= '</select>';
        $html .= '</div></div>';
        return $html;
    }

    function extractSelectData($data)
    {
        $result = [];
        foreach ($data as $item) {

            $keys = array_keys($item);
            $index = $item[$keys[0]];
            $value = $item[$keys[1]];
            $result[$index] = $value;
        }
        return $result;
    }
    /**
     * Define a form element input parameters
     */
    public function addRow($name, $value, $label, $type = 'text', $required = false, $params = [])
    {

        $params['value'] = $value ?? 'valeur par default';
        $params['label'] = $label;
        $params['type'] = $type;
        $params['required'] = $required;
        $this->formParams[$name] = $params;

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
        // var_dump($matches);

        $methodName = 'add' . ucfirst($matches[0]);
        $type = isset($matches[1]) ? $matches[1] : null;
        // var_dump($matches[1]);
        // die();
        // call_user_func(array($this, $methodName), [$key]);
        // if ($rowType === 'select') {
        // } else {
        //     $methodName = 'addInput';
        // }
        // call_user_func(array($this, $methodName), [$key]);
        $input = $this->$methodName($key, $type);
        return $input;
    }



    /**
     * Render the global html of the form
     * @return string 
     */
    public function render()
    {
        // $html = '<form method="POST">';
        $html = $this->getHtml();
        // $html .= '</form>';
        return $this->getHtml();
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
     * Get the current data of the form
     * @return array 
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Generate the full form's html
     * @return string $html 
     */
    private function getHtml(): string
    {
        $html = '';
        return $html;
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
