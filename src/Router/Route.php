<?php

namespace App\Router;


class Route
{
    protected $path;
    protected $callable;
    protected $matches;
    protected $namedRoute;


    public function __construct($path, $callable, $namedRoute = null)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
        $this->namedRoute = $namedRoute;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }



    public function call()
    {
        if ($this->namedRoute) {
            $action = explode('.', $this->namedRoute);
            $controllerName =  ucfirst('App\\Controller\\' . ucfirst($action[0]));
            $task = $action[1];
            $controller = new $controllerName();
            return call_user_func_array([$controller, $task], $this->matches);
        }
        return call_user_func_array($this->callable, $this->matches);
    }
}
