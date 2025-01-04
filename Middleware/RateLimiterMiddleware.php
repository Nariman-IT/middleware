<?php

require_once 'MiddlewareInterface.php';

class RateLimiterMiddleware implements MiddlewareInterface
{
    public function handle($request, callable $next)
    {
        if(! $request['rate_limit']){
            throw new Exception("[RateLimiter] Limit check failed.");
        }
        
            echo "[RateLimiter] Limiting chek passed." . PHP_EOL;
            return $next($request);
        
    }
}