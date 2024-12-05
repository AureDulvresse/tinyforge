<?php

namespace Forge\Http\Controllers;

use Forge\Http\Controllers\BaseController;

class ErrorController extends BaseController
{
    /**
     * Affiche la page 404.
     */
    public function show404($request)
    {
        // Définir le code de réponse HTTP 404
        http_response_code(404);

        // Charger la vue 404
        $this->view('errors/404', [
            'title' => 'Page non trouvée',
            'message' => "La page que vous recherchez n'existe pas ou a été déplacée."
        ]);
    }
}
