<?php
namespace Caiquebispo\Project\Core;
class Route
{
    protected static ?array $routes = [];
    private static function addRoute($route, $controller, $action, $method): void
    {

       self::$routes[$method][] = ['route' => $route,'controller' => $controller, 'method' => $action];
    }

    public static function get($route, array|\Closure $params): void
    {
        if($params instanceof \Closure){
            call_user_func($params);
            return;
        }

        self::addRoute($route, $params[array_key_first($params)], $params[array_key_last($params)], "GET");
    }
    public static function delete($route, array|\Closure $params): void
    {
        if($params instanceof \Closure){
            call_user_func($params);
            return;
        }

        self::addRoute($route, $params[array_key_first($params)], $params[array_key_last($params)], "DELETE");
    }
    public static function put($route, array|\Closure $params): void
    {
        if($params instanceof \Closure){
            call_user_func($params);
            return;
        }

        self::addRoute($route, $params[array_key_first($params)], $params[array_key_last($params)], "PUT");
    }

    public static function post($route, array|\Closure $params): void
    {
        if($params instanceof \Closure){
            call_user_func($params);
            return;
        }

        self::addRoute($route, $params[array_key_first($params)], $params[array_key_last($params)], "POST");
    }

    public static function dispatch(): void
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method =  $_SERVER['REQUEST_METHOD'];

        $index_route = array_search($uri,
            array_column(self::$routes[$method], 'route')
        );

        if($index_route !== false){

            $controller = new self::$routes[$method][$index_route]['controller'];
            $method = self::$routes[$method][$index_route]['method'];
            $controller->$method();

        } else {
            throw new \Exception("No route found for URI: $uri");
        }
    }

}