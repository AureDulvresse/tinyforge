<?php

namespace Forge\Http\Middlewares;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Forge\Auth\Session;

class AuthMiddleware extends AbstractMiddleware
{
    /**
     * Vérifie si l'utilisateur est connecté avant de passer à la requête suivante.
     * @param Request $request La requête HTTP
     * @param callable $next Fonction pour appeler le middleware suivant
     * @return Response
     */
    public function handle(Request $request, callable $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Session::get('user_id')) {
            // Retourner une réponse 403 (Forbidden) si l'utilisateur n'est pas authentifié
            return new Response("Accès interdit : vous devez être connecté.", 403);
        }

        // Appeler le middleware suivant dans la chaîne
        return $next($request);
    }
}
