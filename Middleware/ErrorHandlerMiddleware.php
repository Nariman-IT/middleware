<?php

require_once 'MiddlewareInterface.php';

// Обработка ошибок
class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {

        try {
            // Пробуем передать запрос дальше 
            return $next($request);
        } catch (Exception $e) {
            //Ловим ошибку и формируем ответ
            echo "[ErrorHandler] Caught exception: " . $e->getMessage() . PHP_EOL;

            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }




        // Логируем начало обработки
        echo "[Logger] Handling request: " . json_encode($request) . PHP_EOL;
       
        $response = $next($request); // Передаем управления дальше

        // Логируем конец обработки
        echo "[Logger] Response generated: " . json_encode($response) . PHP_EOL;

        return $response;
    }
}