<?php

namespace Core;

use Core\Router\Router;

class Application
{
    protected static $db;
    protected static $_instance;

    /**
     * Return current instance of Application
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Application();
        }
        return self::$_instance;
    }

    /**
     * Get current instance of Database
     * @return Database $db
     */
    public static function getDb()
    {
        self::$db = new Database(DBNAME, DBHOST, DBUSER, DBPASSWORD);
        return self::$db;
    }

    /**
     * Load a model from gien name $modelName
     * @param string $modelName - Model name. Ex: 'Posts', 'Comments',...
     * @return Model
     */
    public static function getModel(string $modelName): Model
    {
        $modelName = ucfirst(strtolower($modelName));
        $fqn = '\\App\\Model\\' . $modelName;
        self::getDb();
        return new $fqn(self::getDb());
    }

    /**
     * Process Application
     * @return void
     */
    public function process(): void
    {
        // Router
        $router = new Router($_GET['url']);

        $router->get('/attributes', 'attributes.index');
        $router->get('/attributes/add', 'attributes.add');
        $router->get('/attributes/edit/:id', 'attributes.edit');
        $router->get('/attributes/:id', 'attributes.view');

        $router->post('/attributes/add', 'attributes.add');
        $router->post('/attributes/edit/:id', 'attributes.edit');

        // $router->post('/attribute/:id', function ($id) {
        //     echo "I'm adding attributes number $id";
        // });

        $router->run();
    }
}
