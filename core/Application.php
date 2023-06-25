<?php

namespace Core;

use Core\Router\Router;
use Exception;

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
     * @return ?Model
     */
    public static function getModel(string $modelName): ?Model
    {
        $modelName = ucfirst(strtolower($modelName));
        $fqn = '\\App\\Model\\' . $modelName;

        // if (!class_exists($fqn, false)) {
        //     throw new Exception("Cette classe nexiste pas", 1);
        // }

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

        // Home
        $router->get('/', 'home.index');
        $router->get('/home', 'home.index');
        $router->get('/404', 'app.notFound');

        // Attributes
        $router->get('/attributes', 'attributes.index');
        $router->get('/attributes/:filter', 'attributes.index');
        $router->get('/attributes/add', 'attributes.add');
        $router->get('/attributes/delete/:id', 'attributes.delete');
        $router->get('/attributes/edit/:id', 'attributes.edit');
        $router->get('/attributes/:id', 'attributes.view');

        $router->post('/attributes', 'attributes.index');
        $router->post('/attributes/delete/:id', 'attributes.delete');
        $router->post('/attributes/add', 'attributes.add');
        $router->post('/attributes/edit/:id', 'attributes.edit');


        // Hidings
        $router->get('/hidings', 'hidings.index');
        $router->get('/hidings/add/', 'hidings.add');
        $router->get('/hidings/edit/:id', 'hidings.edit');
        $router->get('/hidings/delete/:id', 'hidings.delete');

        $router->post('/hidings/edit/:id', 'hidings.edit');
        $router->post('/hidings/delete/:id', 'hidings.delete');
        $router->post('/hidings/add', 'hidings.add');

        // Users
        $router->get('/users', 'users.index');
        $router->get('/users/:filter', 'users.index');
        // $router->get('/users/:id', 'users.view');
        $router->get('/users/add/', 'users.add');
        $router->get('/users/add/:userType', 'users.add');
        $router->get('/users/edit/:id', 'users.edit');
        $router->get('/users/delete/:id', 'users.delete');
        $router->get('/signin', 'users.signIn');
        $router->get('/login', 'users.login');
        $router->get('/logout', 'users.logout');

        $router->post('/login', 'users.login');
        $router->post('/users/add/', 'users.add');
        $router->post('/users/add/:userType', 'users.add');
        $router->post('/users/edit/:id', 'users.edit');
        $router->post('/users/delete/:id', 'users.delete');

        // Missions
        $router->get('/missions', 'missions.index');
        $router->get('/missions/edit/:id', 'missions.edit');
        $router->get('/missions/add/', 'missions.add');
        $router->get('/missions/add/:action', 'missions.add');

        $router->post('/missions/add/', 'missions.add');
        $router->post('/missions/add/:action', 'missions.add');

        $router->post('/missions/edit/:id', 'missions.edit');

        $router->run();
    }
}
