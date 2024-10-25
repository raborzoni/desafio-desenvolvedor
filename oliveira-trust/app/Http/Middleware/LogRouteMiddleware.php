<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRouteMiddleware
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
        // Logando a requisição recebida e a rota esperada
        Log::info('Rota acessada:', [
            'url' => $request->url(),
            'method' => $request->method(),
            'route' => $request->route() ? $request->route()->getName() : 'Nenhuma rota encontrada',
            'parameters' => $request->route() ? $request->route()->parameters() : []
        ]);

        return $next($request);
    }
}
