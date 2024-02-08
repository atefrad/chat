<?php 

namespace App\Core\Routing\Contracts;

interface RouteInterface 
{
    public static function add(string $route, array $action);

    public static function get(string $route, array $action);

    public static function post(string $route, array $action);
    
    public static function find(string $route, string $method = 'get');
}