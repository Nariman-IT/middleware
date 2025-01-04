<?php

interface MiddlewareInterface 
{
    public function handle($request, callable $next);
}