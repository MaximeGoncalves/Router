<?php
/**
 * Created by PhpStorm.
 * User: maxime
 * Date: 04/04/18
 * Time: 10:26
 */

namespace Softease\Router;


class Route
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var
     */
    private $matches;

    public function __construct(string $url, $callable)
    {
        $this->url = trim($url, '/');
        $this->callable = $callable;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#{([\w]+)}#', '([^/]+)', $this->url);
        $regex = "#^$path$#i";

        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function call(){
        if(is_string($this->callable)){
            $params = explode('#',$this->callable);
            $controller = "Softease\\Controller\\". $params[0]. 'Controller';
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        }else{
        return call_user_func_array($this->callable, $this->matches);
        }
    }
}