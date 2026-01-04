<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Convert404To204
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Convertir les réponses 404 en 204 tout en gardant le contenu original
        if ($response->getStatusCode() === 404) {
            $response->setStatusCode(204);
        }

        return $response;
    }
}
