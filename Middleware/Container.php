<?php


    // Cтандартный подход для реализации Dependency Injection контейнеров
    // Этот подход позволяет контейнеру поддерживать как статические значения, так и 
    // фабрики (замыкания, создающие объекты).
class Container
{
    private $bindings = [];
    
    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;
    }


    public function resolve($key)
    {
        if(! isset($this->bindings[$key])){
            // Автоматическая инстанция класса, если его нет в контейнере
            if(class_exists($key)){
                return new $key;
            }

            throw new Exception("No binding found for $key!");
            
        }

        return is_callable($this->bindings[$key]) ? $this->bindings[$key]() : $this->bindings[$key];
    }
}