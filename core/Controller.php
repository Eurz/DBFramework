<?php

namespace Core;

use App\Model\AppModel;
use Core\Renderer;

class Controller
{
    protected $model;

    /**
     * @var array|string $roles - List of required roles
     */
    protected $roles;

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
     * Load a model - Default name = name from called class
     * @param string $modelName - Load a model from name $modelName. Default name is model for the current controller
     * @return Model $model - Model to fetch data
     */
    public function getModel(string $modelName = null): Model
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

    /**
     * Redirection to a specific url
     */
    protected function redirect($url)
    {
        // header("HTTP/1.1 301 Moved Permanently");

        Http::redirect($url);
    }

    /**
     * Display page 404
     */
    public function notFound()
    {
        $pageTitle = 'Page not found';
        header("HTTP/1.1 404 Not Found");
        $this->render('error', compact('pageTitle'));
        exit();
        // $this->redirect('/404', compact('pageTitle'));
    }

    public function errorPage()
    {
        # code...
    }
}
