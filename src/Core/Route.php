<?php

namespace Caiquebispo\Project\Core;

class Route
{
    private static array $routes = [];

    public static function get(string $uri, callable|array $action): void
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, callable|array $action): void
    {
        self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, callable|array $action): void
    {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete(string $uri, callable|array $action): void
    {
        self::addRoute('DELETE', $uri, $action);
    }

    private static function addRoute(string $method, string $uri, callable|array $action): void
    {
        self::$routes[$method][$uri] = $action;
    }

    public static function dispatch(): void
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset(self::$routes[$method])) {
            self::notFound();
            return;
        }

        foreach (self::$routes[$method] as $route => $action) {
            $routePattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
            $routePattern = '#^' . $routePattern . '$#';

            if (preg_match($routePattern, $url, $matches)) {
                $parameters = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

                if (is_array($action)) {
                    [$controller, $method] = $action;
                    call_user_func_array([new $controller, $method], [...$parameters]);
                } else {
                    call_user_func_array($action, [...$parameters]);
                }
                return;
            }
        }

        self::notFound();
    }

    private static function notFound(): void
    {
        http_response_code(404);
        echo "Rota não encontrada";
        // Retornar alguma view de erro aqui, por enquanto deixa apenas o echo
    }
}
