<?php

namespace App\Router;

use Exception;

class Router
{
    private $url;
    private $routes = [];


    public function __construct($url)
    {
        // $this->url =  $url;
        $this->url = trim($url, '/');
        // $text = 't@otototo123.com';
        // $url = 'articles/edit/124';
        // $result = preg_match('/^([a-z]*)\/([]a-z]*)\/([0-9]*)/i', $url, $matches);
        // // $result = preg_match('/^[a-z0-9A-Z]+\@[a-z0-9]+\.[a-z]{2,3}$/', $text, $matches);
        // var_dump($result, $matches);
    }

    public function get($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, $name, 'GET');
    }


    public function post($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, $name, 'POST');
    }

    public function addRoute($path, $callable, $name, $method)
    {
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }

        $route = new Route($path, $callable, $name);
        $this->routes[$method][] = $route;


        // if($name){
        //     $this->namedRoutes[$name] = $route;
        // }
        return $route;
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new Exception('No data to treat');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new Exception('No matching routes');
    }
}
