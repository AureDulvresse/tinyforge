<?php

namespace Forge\Auth;

class Session
{
    // Démarre une session si elle n'existe pas déjà
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Enregistre une valeur dans la session avec la clé fournie
    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    // Récupère la valeur associée à la clé fournie, retourne null si elle n'existe pas
    public static function get($key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function exists($key): mixed
    {
        return isset($_SESSION[$key]) ;
    }

    // Détruit la session en cours
    public static function destroy(): void
    {
        session_destroy();
    }
}
