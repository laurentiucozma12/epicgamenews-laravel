<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreprodAccess
{
    public function handle(Request $request, Closure $next): Response
    {    
        $currentBranch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));
        if ($currentBranch === 'pre-production') 
        {
            $clientIP = $request->ip();   
            if ($clientIP === '83.103.225.235' || $clientIP === '5.14.139.23')
            {
                return $next($request);
            }    
            else {
                abort(403, 'Access Denied | Unauthorized');
            }
        }

        return $next($request);
    }
}
