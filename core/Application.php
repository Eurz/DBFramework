<?php

namespace Core;


class Application
{
    protected static $db;
    protected static $_instance;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Application();
        }
        return self::$_instance;
    }

    public static function getDb()
    {
        self::$db = new Database(DBNAME, DBHOST, DBUSER, DBPASSWORD);
        return self::$db;
    }

    public static function getModel($modelName)
    {

        $modelName = ucfirst(strtolower($modelName));
        $fqn = '\\App\\Model\\' . $modelName;
        self::getDb();
        return new $fqn(self::getDb());
    }

    public function process()
    {
        // Router
        $router = new \App\Router\Router($_GET['url']);

        $router->get('/', function () {
            echo "I'm the home page";
        }, 'attributes.index');
        $router->get('/attribute', 'attributes.index');
        $router->get('/attributes/add', 'attributes.create');


        $router->get('/attributes/edit/:id', 'attributes.edit');

        $router->get('/attributes/:id', 'attributes.view');
        $router->post('/attributes/add', 'attributes.create');


        $router->post('/attribute/:id', function ($id) {
            echo "I'm adding attributes number $id";
        });




        $router->run();
    }
}
