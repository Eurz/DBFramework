<?php

namespace Core;

use App\Model\Attributes;
use Core\Renderer;

class Controller
{
    protected $model;


    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function render($viewPath, $data = [])
    {
        $renderer = new Renderer();
        $renderer->render($viewPath, $data);
    }


    /**
     * 
     */
    public function query()
    {
        # code...
    }


    /**
     * Load a model - Default = name from called class
     */
    public function getModel(string $modelName = null)
    {
        if ($modelName === null) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $modelName = end($classNameParts);
        }


        $app = Application::getInstance();
        $model = $app::getModel($modelName);
        return $model;
    }
}
