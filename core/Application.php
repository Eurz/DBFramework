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

        // Install
        $router->get('/install', 'home.index');
        $router->post('/install', 'home.index');

        // Home
        $router->get('/', 'missions.index');
        $router->get('/home', 'missions.index');
        $router->get('/404', 'app.notFound');

        // $router->post('/', 'home.index');

        // Attributes
        $router->get('/attributes', 'attributes.index');
        $router->get('/attributes/add/:type', 'attributes.add');
        // $router->get('/attributes/:filter', 'attributes.index');
        $router->get('/attributes/delete/:id', 'attributes.delete');
        $router->get('/attributes/edit/:id', 'attributes.edit');
        $router->get('/attributes/:id', 'attributes.view');

        $router->post('/attributes', 'attributes.index');
        $router->post('/attributes/delete/:id', 'attributes.delete');
        $router->post('/attributes/add/:type', 'attributes.add');
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
        $router->get('/users/missions', 'users.missions');

        $router->get('/users/edit/:id', 'users.edit');
        $router->get('/users/delete/:id', 'users.delete');
        $router->get('/signin', 'users.signIn');
        $router->get('/login', 'app.login');
        $router->get('/logout', 'app.logout');

        $router->post('/login', 'app.login');
        $router->post('/users/add/', 'users.add');
        $router->post('/users/add/:userType', 'users.add');
        $router->post('/users/edit/:id', 'users.edit');
        $router->post('/users/delete/:id', 'users.delete');

        // Missions
        $router->get('/missions', 'missions.index');
        $router->get('/missions/view/:id', 'missions.view');
        $router->get('/missions/edit/:id', 'missions.edit');
        $router->get('/missions/edit/:id/:action', 'missions.edit');
        $router->get('/missions/add/', 'missions.add');
        $router->get('/missions/add/:action', 'missions.add');
        $router->get('/missions/delete/:id', 'missions.delete');


        $router->post('/missions/add/', 'missions.add');
        $router->post('/missions/add/:action', 'missions.add');

        $router->post('/missions/edit/:id', 'missions.edit');
        $router->post('/missions/edit/:id/:action', 'missions.edit');
        $router->post('/missions/delete/:id', 'missions.delete');


        // SEARCH 
        $router->get('/search', 'search.index');
        $router->post('/search', 'search.index');


        $router->run();
    }
}
