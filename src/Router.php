<?php

namespace Softease\Router;

class Router
{

    private $url;
    private $routes = [];
    private $routesName = [];

    public function __construct($url)
    {

        $this->url = $url;
    }

    /**
     * Créer une route de type GET
     *
     * @param string      $url Url à joindre
     * @param callable    $callable Function à executer lors de l'appelle de la route
     * @param string|null $name Nom de la route
     */
    public function get(string $url, $callable, string $name = null)
    {
        $route = new Route($url, $callable);
        $this->routes['GET'][] = $route;
        $this->routesName[$name] = $route;

    }

    /**
     * Créer une route de type POST
     *
     * @param string      $url Url à joindre
     * @param callable    $callable Function à executer lors de l'appelle de la route
     * @param string|null $name Nom de la route
     */
    public function post(string $url, $callable, string $name = null)
    {
        $route = new Route($url, $callable);
        $this->routes['POST'][] = $route;
        $this->routesName[$name] = $route;
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD DOES NOT EXIST');
        }
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        throw new RouterException('NO MATCHING ROUTE');
    }
}

