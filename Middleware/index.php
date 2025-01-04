<?php

require_once 'MiddlewareInterface.php';
require_once 'Container.php';
require_once 'MiddlewareDispatcher.php';
require_once 'Route.php';
require_once 'AuthMiddleware.php';
require_once 'RateLimiterMiddleware.php';
require_once 'LoggerMiddleware.php';
require_once 'ErrorHandlerMiddleware.php';





function coreHandler($request)
{
    echo "[CoreHadler] Processing request..." . PHP_EOL; 

    // Возвращает успешный ответ
    return [
        'success' => true,
        'message' => 'Request processed successfully!',
    ];
}



$request = [
    'user' => [
        'name' => 'Nariman',
        'authenticated' => true,
    ],
    'rate_limit' => true,
];





$container = new Container();
$dispatcher = new MiddlewareDispatcher('coreHandler', $container);


// Регистрация именованного Middleware
$dispatcher->registerNamedMiddleware('auth', AuthMiddleware::class);
$dispatcher->registerNamedMiddleware('logger', LoggerMiddleware::class);
$dispatcher->registerNamedMiddleware('rate_limiter', RateLimiterMiddleware::class);
$dispatcher->registerNamedMiddleware('error', ErrorHandlerMiddleware::class);


// Добавление группы Middleware
$dispatcher->addGroup('web', ['auth', 'logger', 'error']);
$dispatcher->addGroup('api', ['rate_limiter']);


// Пример маршрута
$route = new Route('/example', function ($request){
    return [
        'success' => true,
        'message' => 'Routeprocessed successfully!',
    ];
});

// Применяем группы к маршруту 
$route->addMiddleware('auth');
$route->addMiddleware('logger');
$route->addMiddleware('error');
$route->addMiddleware('rate_limiter');


// Выполняем обработку маршрута
$response = $route->handle($request, $dispatcher);

echo "[Result]" . json_encode($response) . PHP_EOL;

