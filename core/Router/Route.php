<?php

namespace Core\Router;

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

    /**
     * Match an url with this route's url
     * @param string $url - Url to match with
     * @return bool - True if it is url macthes otherwise false
     */
    public function match(string $url): bool
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


    /**
     * Call this route
     * @return callable
     */
    public function call()
    {
        if ($this->namedRoute) {
            $action = explode('.', $this->namedRoute);
            $controllerName =  ucfirst('App\\Controller\\' . ucfirst($action[0]) . 'Controller');
            $task = $action[1];
            $controller = new $controllerName();
            return call_user_func_array([$controller, $task], $this->matches);
        }
        return call_user_func_array($this->callable, $this->matches);
    }
}
