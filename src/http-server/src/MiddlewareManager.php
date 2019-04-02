<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://hyperf.org
 * @document https://wiki.hyperf.org
 * @contact  group@hyperf.org
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Hyperf\HttpServer;

class MiddlewareManager
{
    /**
     * @var array
     */
    public static $container = [];

    public static function addMiddleware(string $path, string $method, string $middleware): void
    {
        $method = strtoupper($method);
        static::$container[$path][$method][] = $middleware;
    }

    public static function addMiddlewares(string $path, string $method, array $middlewares): void
    {
        $method = strtoupper($method);
        foreach ($middlewares as $middleware) {
            static::$container[$path][$method][] = $middleware;
        }
    }

    public static function get(string $rule, string $method): array
    {
        $method = strtoupper($method);
        return static::$container[$rule][$method] ?? [];
    }
}