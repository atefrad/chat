<?php 

namespace App\Core\Routing;

use App\Core\Routing\Contracts\RouteInterface;

class Route implements RouteInterface
{
    private static array $routes = [];

    public static function add(string $route, array $action)
    {
        self::$routes[$route] = $action;
    }

    public static function get(string $route, array $action)
    {
        self::$routes['get'][$route] = $action;
    }

    public static function post(string $route, array $action)
    {
        self::$routes['post'][$route] = $action;
    }

    public static function find(string $route, string $method = 'get')
    {
        $foundRoute = self::$routes[$method][$route];

        $controller = $foundRoute[0];

        $method = $foundRoute[1];

        return (new $controller())->{$method}();
    }
}