<?php

namespace App\Core;

use Exception;
/**
 * Router — mantido tal como fornecido pelo utilizador.
 */
// class Router {
//     private static array $routes = [];
//     private static $notFoundHandler = null;

//     public static function add(string $method, string $route, $callback, array $middlewares = []): void {
//         self::$routes[] = [
//             'method'      => strtoupper($method),
//             'route'       => self::normalize($route),
//             'callback'    => $callback,
//             'middlewares' => $middlewares,
//         ];
//     }

//     public static function setNotFound($callback): void { self::$notFoundHandler = $callback; }

//     public static function run(): void {
//         $url = isset($_GET['url']) ? self::normalize($_GET['url']) : '';
//         $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
//         if ($method === 'HEAD') $method = 'GET';

//         foreach (self::$routes as $r) {
//             if ($method !== $r['method']) continue;
//             [$regex, $paramNames] = self::compile($r['route']);

//             if (preg_match($regex, $url, $matches)) {
//                 array_shift($matches);
//                 $params = [];
//                 foreach ($paramNames as $i => $name) $params[$name] = $matches[$i] ?? null;

//                 foreach ($r['middlewares'] as $mw) {
//                     if (is_string($mw)) {
//                         $result = null;
//                         if (function_exists($mw)) {
//                             $result = call_user_func_array($mw, array_values($params));
//                         } elseif (strpos($mw, '@') !== false) {
//                             [$class, $methodName] = explode('@', $mw, 2);
//                             if (class_exists($class) && method_exists($class, $methodName)) {
//                                 $result = call_user_func_array([$class, $methodName], array_values($params));
//                             } else throw new Exception("Middleware $mw não existe.");
//                         } else throw new Exception("Middleware $mw não é válido.");
//                         if ($result === false) return;
//                     } elseif (is_callable($mw)) {
//                         if (call_user_func_array($mw, array_values($params)) === false) return;
//                     }
//                 }

//                 if (is_string($r['callback']) && strpos($r['callback'], '@') !== false) {
//                     [$class, $methodName] = explode('@', $r['callback'], 2);
//                     if (class_exists($class) && method_exists($class, $methodName)) {
//                         call_user_func_array([new $class, $methodName], array_values($params));
//                     } else throw new Exception("Controller ou método não encontrado: {$r['callback']}");
//                 } else {
//                     call_user_func_array($r['callback'], array_values($params));
//                 }
//                 return;
//             }
//         }

//         http_response_code(404);
//         if (self::$notFoundHandler) call_user_func(self::$notFoundHandler);
//         else die('Pagina nao encotrada');
//     }

//     private static function normalize(string $path): string {
//         return trim(trim($path), '/');
//     }

//     private static function compile(string $route): array {
//         $paramNames = [];
//         $regex = '';
//         $offset = 0;

//         if (preg_match_all('#\{([a-zA-Z_][a-zA-Z0-9_]*)(\?)?\}#', $route, $m, PREG_OFFSET_CAPTURE)) {
//             foreach ($m[0] as $idx => $match) {
//                 $token = $match[0];
//                 $pos = $match[1];
//                 $name = $m[1][$idx][0];
//                 $isOptional = $m[2][$idx][0] === '?';
//                 $static = substr($route, $offset, $pos - $offset);
//                 $staticEndsWithSlash = (substr($static, -1) === '/');

//                 if ($isOptional && $staticEndsWithSlash) {
//                     $static = substr($static, 0, -1);
//                     $regex .= preg_quote($static, '#') . '(?:/([^/]+))?';
//                 } else {
//                     $regex .= preg_quote($static, '#');
//                     $regex .= $isOptional ? '([^/]+)?' : '([^/]+)';
//                 }
//                 $paramNames[] = $name;
//                 $offset = $pos + strlen($token);
//             }
//         }
//         $regex .= preg_quote(substr($route, $offset), '#');
//         return ['#^' . $regex . '$#', $paramNames];
//     }
// }




class Router
{
    private static array $routes = [];
    private static $notFoundHandler = null;
    private static ?array $currentRoute = null;

    public static function add(
        string $method,
        string $route,
        callable|array|string $callback,
        array $middlewares = [],
        ?string $name = null
    ): void {
        self::$routes[] = [
            'method' => strtoupper($method),
            'route' => self::normalize($route),
            'callback' => $callback,
            'middlewares' => $middlewares,
            'name' => $name,
        ];
    }

    public static function get(
        string $route,
        callable|array|string $callback,
        array $middlewares = [],
        ?string $name = null
    ): void {
        self::add('GET', $route, $callback, $middlewares, $name);
    }

    public static function post(
        string $route,
        callable|array|string $callback,
        array $middlewares = [],
        ?string $name = null
    ): void {
        self::add('POST', $route, $callback, $middlewares,$name);
    }

    public static function put(
        string $route,
        callable|array|string $callback,
        array $middlewares = []
    ): void {
        self::add('PUT', $route, $callback, $middlewares);
    }

    public static function delete(
        string $route,
        callable|array|string $callback,
        array $middlewares = []
    ): void {
        self::add('DELETE', $route, $callback, $middlewares);
    }

    public static function setNotFound(callable $callback): void
    {
        self::$notFoundHandler = $callback;
    }

    public static function currentRoute(): ?string
    {
        return self::$currentRoute['name'] ?? null;
    }

    public static function run(): void
    {
        $url = isset($_GET['url'])
            ? self::normalize($_GET['url'])
            : '';

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'HEAD') {
            $method = 'GET';
        }

        foreach (self::$routes as $route) {

            if ($method !== $route['method']) {
                continue;
            }

            [$regex, $paramNames] = self::compile($route['route']);

            if (!preg_match($regex, $url, $matches)) {
                continue;
            }

            // Guarda a rota ativa
            self::$currentRoute = $route;

            array_shift($matches);

            $params = [];

            foreach ($paramNames as $i => $name) {
                $params[$name] = $matches[$i] ?? null;
            }

            // Middlewares
            foreach ($route['middlewares'] as $middleware) {

                $result = self::call($middleware, $params);

                if ($result === false) {
                    return;
                }
            }

            // Controller / callback
            self::call($route['callback'], $params);

            return;
        }

        http_response_code(404);

        if (self::$notFoundHandler) {
            call_user_func(self::$notFoundHandler);
            return;
        }

        die('Página não encontrada');
    }

    private static function call(
        callable|array|string $callback,
        array $params = []
    ): mixed {
        // [ClassName::class, 'method']
        if (is_array($callback)) {

            [$class, $method] = $callback;

            if (!class_exists($class)) {
                throw new Exception(
                    "Classe não encontrada: {$class}"
                );
            }

            if (!method_exists($class, $method)) {
                throw new Exception(
                    "Método não encontrado: {$class}::{$method}"
                );
            }

            return call_user_func_array(
                [new $class, $method],
                array_values($params)
            );
        }

        // callable normal
        if (is_callable($callback)) {
            return call_user_func_array(
                $callback,
                array_values($params)
            );
        }

        throw new Exception('Callback inválido.');
    }

    private static function normalize(string $path): string
    {
        return trim(trim($path), '/');
    }

    private static function compile(string $route): array
    {
        $paramNames = [];
        $regex = '';
        $offset = 0;

        if (preg_match_all(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)(\?)?\}#',
            $route,
            $matches,
            PREG_OFFSET_CAPTURE
        )) {
            foreach ($matches[0] as $index => $match) {

                $token = $match[0];
                $position = $match[1];

                $name = $matches[1][$index][0];

                $isOptional = $matches[2][$index][0] === '?';

                $static = substr(
                    $route,
                    $offset,
                    $position - $offset
                );

                $staticEndsWithSlash =
                    substr($static, -1) === '/';

                if ($isOptional && $staticEndsWithSlash) {

                    $static = substr($static, 0, -1);

                    $regex .=
                        preg_quote($static, '#') .
                        '(?:/([^/]+))?';

                } else {

                    $regex .= preg_quote($static, '#');

                    $regex .= $isOptional
                        ? '([^/]+)?'
                        : '([^/]+)';
                }

                $paramNames[] = $name;

                $offset = $position + strlen($token);
            }
        }

        $regex .= preg_quote(
            substr($route, $offset),
            '#'
        );

        return [
            '#^' . $regex . '$#',
            $paramNames
        ];
    }
}
