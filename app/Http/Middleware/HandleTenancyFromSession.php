<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleTenancyFromSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = session(config('tenancy.company_id_session_key'));
        if ($companyId) {
            tenancy()->initialize($companyId);
        }

        return $next($request);
    }
}
