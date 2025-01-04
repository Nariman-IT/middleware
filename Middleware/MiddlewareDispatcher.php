<?php

// Диспетчер Middleware
class MiddlewareDispatcher
{

    private $middlewares = [];
    private $groups = [];
    private $namedMiddlewares = [];
    private $coreHandler;
    private $container;



    public function __construct(callable $coreHandler, Container $container)
    {
        $this->coreHandler = $coreHandler; // Финальный обработчик
        $this->container = $container; // Dependency Injection
    }

    
    // Добавления Middlevare c поддержкой приоритетов
    public function addMiddleware($middleware, int $priority = 0): void 
    {

        if (is_string($middleware)) {
            $middleware = $this->namedMiddlewares[$middleware] ?? null;
            if(! is_null($middleware)){
                $middleware = $this->container->resolve($middleware);
            } else {
                throw new Exception("Named middleware '{$middleware}' not found.");
            }
        }
        

        $this->middlewares[] = [
            'middleware' => $middleware,
            'priority' => $priority,
        ];

    }


    
    // Регистрация именованного Middleware
    public function registerNamedMiddleware(string $name, MiddlewareInterface|string $middleware)
    {
        $this->namedMiddlewares[$name] = $middleware;
    }



    // Использовани именованного Middleware
    public function useNamedMiddleware(string $name)
    {
        if (! isset($this->namedMiddlewares[$name])){
            throw new Exception("Named middleware name $name not found!");
        }
    
        $this->addMiddleware($this->namedMiddleware[$name]);
    }



    // Добавления групп Middleware
    public function addGroup(string $groupName, array $middlewareList): void
    {
        $this->groups[$groupName] = $middlewareList;
    }



    // Прменение группы Middleware
    public function useGroup(string $groupName)
    {
        if(! isset($this->groups[$groupName])){
            throw new Exception("Middleware group $groupName not found!");
        }

        foreach($this->groups[$groupName] as $middleware){
            $this->addMiddleware($middleware); 
        }
    }



    // Dispatch Middleware
    public function dispatch($request)
    {

        // Сортировка по приоритету
        usort($this->middlewares, function($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });


        // Построение цепочки
        $next = $this->coreHandler;

        foreach(array_reverse($this->middlewares) as $entry) {
            $middleware = $entry['middleware'];

            $next = function($request) use ($middleware, $next){
                return $middleware->handle($request, $next);
            };
        }
        
        
        // Запускаем цепочку
        return $next($request);
    }
}