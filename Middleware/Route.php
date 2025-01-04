<?php

class Route 
{
    private $path;
    private $action;
    private $middlewares = [];

    public function __construct(string $path, callable $action)
    {
        $this->path = $path;
        $this->action = $action;
    }


    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }


    public function handle($request, MiddlewareDispatcher $dispatcher)
    {
        foreach($this->middlewares as $middleware){
            $dispatcher->addMiddleware($middleware);
        }

        return $dispatcher->dispatch($request);
    }
}