<?php

namespace Forge\Http\Middlewares;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractMiddleware
{
    /**
     * Méthode principale pour gérer le middleware.
     * @param Request $request La requête HTTP
     * @param callable $next Fonction pour appeler le middleware suivant
     * @return Response La réponse HTTP après traitement
     */
    abstract public function handle(Request $request, callable $next): Response;
}
