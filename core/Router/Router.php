<?php

namespace Core\Router;

use Exception;

class Router
{
    /**
     * @var string $url - Current url
     * @var array $routes - All routes declared
     */
    private $url;
    private $routes = [];


    public function __construct(string $url)
    {
        $this->url = trim($url, '/');
    }

    /**
     * Used to declare a 'GET' route
     * @param string $path - An url
     * @param callable|string $callable - Function to use for this url
     * @param ?string $name 
     * @return Route $route - A route object 
     */
    public function get($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, $name, 'GET');
    }

    /**
     * Used to declare a 'POST' route
     * @param string $path - An url
     * @param callable|string $callable - Function to use for this url
     * @param ?string $name 
     * @return Route $route - A route object 
     */
    public function post($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, $name, 'POST');
    }

    /**
     * Add a routes to '$routes'
     * @param string $path - An url
     * @param callable|string $callable - Function to use for this url
     * @param ?string $name 
     * @param string $method - Method : GET, POST, DELETE, UPDATE
     * @return Route $route - A route object 
     */
    public function addRoute(string $path, $callable, ?string $name, string $method)
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

    /**
     * Run the router and call the correct method route otherwise launch an exception
     */
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
