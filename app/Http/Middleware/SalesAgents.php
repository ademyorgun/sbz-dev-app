<?php

namespace App\Http\Middleware;

use Closure;

class SalesAgents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // sales shouldnt we able to access the 4th 
        // appointment and so on assgined to him.
        // The appointment are ordered by visit date in decending order
        if(strtolower(auth()->user()->role->name) == 'sales_agent') {
            $appointmentId = $request->route('appointment');
            // check if appointments with open feedback exist

            // check if its more than 3rd appointment open with 
            // visit date desc order
        }
        return $next($request);
    }
}
