<?php

namespace Core\Forms;

class AsbstractForms
{

    protected $data;
    /**
     * Parameters to create form
     */
    private $formParams;
    /**
     * Message from form submission
     */
    protected $message;

    /**
     * Define a form element with input parameters
     * @param string $name - Name attribute for form html tag.
     * @param $value - Default value for form html tag
     * @param string $label - Title for form's label tag
     * @param string $type - Define form tag and type: input:text, select: multiple, select, textarea ...
     * @param bool $required - Attribute "required" for html tag
     * @param $data - Used for elements of type: select, checkbox, radio...
     * @param array $params - Form Validation Constraints. Ex: notBlank, isString, ... 
     */
    public function addRow($name, $value, $label, $type = 'text', $required = false, $data = null, $params = [])
    {

        $this->formParams[$name] = compact('name', 'value', 'label', 'type', 'required', 'data', 'params');

        return $this;
    }

    /**
     * Display a form html element
     * @param string $key - Key for form html tag in [$formParams]
     * @return ?string $html
     */
    public function row($key): ?string
    {

        if (!$this->has($key)) {
            return null;
        }

        $rowType = $this->formParams[$key]['type'];

        $pregMatch = preg_match('#^(select|input|textarea):?([a-z)]+)?#', $rowType, $matches);

        if ($pregMatch) {
            array_shift($matches);
            $inputType = $matches[0];
            $type = isset($matches[1]) ? $matches[1] : null;

            $methodName = 'add' . ucfirst($inputType);
            $input = $this->$methodName($key, $type, $this->formParams[$key]['data']);
            return $input;
        }
        return null;
    }

    /**
     * @return string $html - Form html code 
     */
    public function render()
    {
        if (!$this->formParams) {
            return 'No forms params';
        }
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
            // $this->data = $_POST;
            return true;
        }
        return false;
    }

    /**
     * Generate html for an form input element
     * @param string $name Name for input's name attribute
     * @param string $type Type of input: 
     * @return string $html
     */
    private function addInput($name, $type)
    {
        $html = '<div class="row mb-3">';
        $html .= $this->createLabel($name);
        $html .= '
            <div class="col-lg-6 col-sm-10 ">
            <input class="form-control" type="' . $type . '" name="' . $name . '" id="' . $name . '" value="' . $this->getValue($name) . '" />
            </div>
            ';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get the current data of the form
     * @return array  $this->data
     */
    public function getData(): array
    {
        return $this->data;
    }
    /**
     * Generate html for an form select element
     * @param string $name Name for input's name attribute
     */
    private function addTextarea($name): string
    {
        $html = '<div class="row mb-3">';
        $html .= $this->createLabel($name);
        $html .= '<div class="col-lg-6 col-sm-10 ">
        <textarea class="form-control" name="' . $name . '" id="' . $name . '">' . $this->getValue($name) . '</textarea>
        </div>';
        $html .= '</div>';

        return $html;
    }
    /**
     * Generate html for an form select element
     * @param string $name - Name for input's name attribute
     * @param string $type - Specify attributes "multiple"
     * @param array|string - Array if attributes "multiple" is defined otherwise string
     */
    private function addSelect($name, $type, $data): string
    {
        $params = $this->formParams[$name];

        $html = '<div class="row mb-3">';
        $html .= '<label for="' . $name . '" class="col-sm-2 form-label">' . $params['label'] . '</label>';
        $html .= '<div class="col-lg-6 col-sm-10 ">';

        if ($type === 'multiple') {
            $ext = '[]';
            // $typeSize = 'size="' . count($data) . '"';
            $typeSize = 'size="4"';
        } else {
            $ext = null;
            $typeSize = null;
        }

        $html .= '<select name="' . $name . $ext . '"  id="' . $name . '" class="form-select" aria-label="' . $name . '" ' . $type . ' ' . $typeSize . '>';
        // $html .= '<option value="">Default</option>';
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $keys = array_keys($v);

                $k = $v[$keys[0]];
                $v = $v[$keys[1]];
            }

            if (is_object($v)) {
                $k = $v->id;
                $v = $v->title;
            }

            if ($type === 'multiple') {
                $selected = array_key_exists($k, $this->getValue($name)) ? 'selected' : null;
            } else {
                $selected = $this->getValue($name) == $k ? 'selected' : null;
            }
            $html .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }
        $html .= '</select>';
        $html .= '</div></div>';
        return $html;
    }

    /**
     * Return label of html element
     * @param string $key 
     * @return string Html code of a label
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
