<?php

namespace Forge\Http;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Http implements HttpInterface
{
    /**
     * Redirige vers une URL spécifique avec des paramètres facultatifs
     * @param string $url
     * @param array $params
     */
    public static function redirect(string $url, array $params = []): void
    {
        // Ajouter des paramètres à l'URL si nécessaire
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        // Créer une réponse de redirection et l'envoyer
        $redirectResponse = new RedirectResponse($url);
        $redirectResponse->send();
    }

    /**
     * Envoie une réponse HTTP avec un statut et un message facultatif
     * @param int $statusCode
     * @param mixed $data
     */
    public static function response(int $statusCode = 200, $data = null): void
    {
        // Vérifier si les données sont présentes
        if (is_null($data)) {
            $data = ''; // Assurez-vous qu'il y a une réponse vide si aucun contenu n'est donné
        }

        // Créer une nouvelle réponse avec les données et le code de statut
        $response = new Response($data, $statusCode);

        // Envoyer la réponse
        $response->send();
    }

    /**
     * Fonction pour envoyer une erreur avec un code d'état
     * @param int $statusCode
     * @param string $message
     */
    public static function errorResponse(int $statusCode, string $message): void
    {
        // Créer une réponse JSON avec un message d'erreur
        $response = new JsonResponse(
            ['error' => $message],
            $statusCode
        );

        // Envoyer la réponse d'erreur
        $response->send();
    }
}
