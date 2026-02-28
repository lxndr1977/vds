<?php

namespace App\Http\Middleware;

use App\Models\SystemConfiguration;
use Closure;
use Illuminate\Http\Request;

class EnsureRegistrationsOpen
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $config = SystemConfiguration::first();

        $open = $config ? $config->registrations_open_to_public : true;

        if (! $open) {
            return redirect()->route('welcome')->with('error', 'Período de inscrições encerrado.');
        }

        return $next($request);
    }
}
