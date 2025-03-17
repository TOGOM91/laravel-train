<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Vous pouvez rediriger vers une page d'accueil ou une page d'erreur si nécessaire
            return route('login'); 
        }
        // Pour une requête API, retourner null provoque l'envoi d'une réponse JSON 401 Unauthorized.
        return null;
    }
    
}
