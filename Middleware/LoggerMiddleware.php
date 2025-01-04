<?php

require_once 'MiddlewareInterface.php';

// Логирование запросво

class LoggerMiddleware implements MiddlewareInterface
{

    public function handle($request, callable $next)
    {
        echo "[Log] Request received: " . json_encode($request) . PHP_EOL;

        $response = $next($request); // Передаем управления дальше

        echo "[Log] Response sent: " . json_encode($request) . PHP_EOL;
        
        return $response;
    }
}