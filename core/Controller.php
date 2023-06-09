<?php

namespace Core;

use Core\Renderer;

class Controller
{
    protected Model $model;


    public function __construct()
    {
    }

    /**
     * Render a view
     * @param string $viewPath - The view path 'directory/filename'. Ex : 'posts/index'
     * @param array $data - Data used to render view
     */
    public function render(string $viewPath, array $data = [])
    {
        $renderer = new Renderer();
        $renderer->render($viewPath, $data);
    }


    /**
     * Under consideration
     */
    public function query()
    {
        # code...
    }


    /**
     * Load a model - Default name = name from called class
     * @param string $modelName - Load a model from name $modelName. Default name is model for the current controller
     * @return ?Model $model - Model to fetch data
     */
    public function getModel(string $modelName = null): ?Model
    {
        if ($modelName === null) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $modelName = end($classNameParts);
            $modelName = str_replace('Controller', '', $modelName);
        }

        $app = Application::getInstance();
        $model = $app::getModel($modelName);


        return $model;
    }

    public function notFound()
    {
<<<<<<< HEAD
        $pageTitle = 'Not found from controller';
        $this->render('error', compact('pageTitle'));
=======
        echo 'Not found from controller';
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
    }

    public function errorPage()
    {
        # code...
    }
}
