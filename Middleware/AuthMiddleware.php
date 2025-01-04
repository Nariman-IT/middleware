<?php

require_once 'MiddlewareInterface.php';

// Проверка авторизации
class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        if(empty($request['user']) || !$request['user']['authenticated']){
            // Если пользователь не авторизован, выбрасываем исключения\
            throw new Exception("Unauthorized: User not authenticated.");
        }

        echo "[Auth] User is authenticated: " . $request['user']['authenticated'] . PHP_EOL;

        // Передаем запрос дальше
        return $next($request);
    }
}