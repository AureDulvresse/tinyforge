<?php

namespace Forge\Http;

interface HttpInterface
{
    /**
     * Redirige vers une URL spécifique avec des paramètres facultatifs
     * @param string $url
     * @param array $params
     */
    public static function redirect(string $url, array $params = []): void;

    /**
     * Envoie une réponse HTTP avec un statut et un message facultatif
     * @param int $statusCode
     * @param mixed $data
     */
    public static function response(int $statusCode = 200, $data = null): void;

    /**
     * Fonction pour envoyer une erreur avec un code d'état
     * @param int $statusCode
     * @param string $message
     */
    public static function errorResponse(int $statusCode, string $message): void;
}
