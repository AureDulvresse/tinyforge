<?php

namespace Forge\Support\Helpers;

class Str
{
    /**
     * Convertit une chaîne en camel case.
     *
     * @param string $string
     * @return string
     */
    public static function camel_case(string $string): string
    {
        // Transforme le séparateur "_" en espaces, met en majuscule chaque mot, puis supprime les espaces
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        // Met en minuscule la première lettre
        return lcfirst($str);
    }

    /**
     * Génère une chaîne aléatoire de la longueur spécifiée.
     *
     * @param int $length
     * @return string
     */
    public static function random(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));  // Génère une chaîne de longueur spécifiée
    }

    /**
     * Formate une date ou une chaîne en objet DateTime en une chaîne au format 'Y-m-d H:i:s'.
     *
     * @param mixed $date Une instance de \DateTime ou une chaîne représentant une date.
     * @return string|null Retourne la date formatée ou null si l'entrée est invalide.
     */
    public static function formatDateToString($date): ?string
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d H:i:s');
        }

        if (is_string($date)) {
            try {
                $parsedDate = new \DateTime($date);
                return $parsedDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                // Si la chaîne n'est pas une date valide
                return null;
            }
        }

        // Si l'entrée n'est ni une chaîne ni un objet DateTime
        return null;
    }

    /**
     * Convertit une chaîne en kebab case.
     *
     * @param string $string
     * @return string
     */
    public static function kebab_case(string $string): string
    {
        return strtolower(trim(preg_replace('/[A-Z]/', '-$0', $string), '-'));
    }

    /**
     * Convertit une chaîne en snake case.
     *
     * @param string $string
     * @return string
     */
    public static function snake_case(string $string): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', $string));
    }

    /**
     * Génère un slug à partir d'une chaîne (utilisé pour les URL).
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function slug(string $string, string $separator = '-'): string
    {
        // Remplace les caractères spéciaux et les espaces par le séparateur
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($string));
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        return str_replace(' ', $separator, $string);
    }

    /**
     * Vérifie si une chaîne est une URL valide.
     *
     * @param string $string
     * @return bool
     */
    public static function is_url(string $string): bool
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Vérifie si une chaîne est une adresse email valide.
     *
     * @param string $email
     * @return bool
     */
    public static function is_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Retourne la première lettre d'une chaîne en majuscule.
     *
     * @param string $string
     * @return string
     */
    public static function ucfirst(string $string): string
    {
        return ucfirst($string);
    }

    /**
     * Retourne la longueur d'une chaîne, ou 0 si la chaîne est vide.
     *
     * @param string $string
     * @return int
     */
    public static function length(string $string): int
    {
        return strlen($string);
    }

    /**
     * Supprime les espaces superflus au début et à la fin d'une chaîne.
     *
     * @param string $string
     * @return string
     */
    public static function trim(string $string): string
    {
        return trim($string);
    }

    /**
     * Transforme une chaîne en minuscules.
     *
     * @param string $string
     * @return string
     */
    public static function lower(string $string): string
    {
        return strtolower($string);
    }

    /**
     * Transforme une chaîne en majuscules.
     *
     * @param string $string
     * @return string
     */
    public static function upper(string $string): string
    {
        return strtoupper($string);
    }
}
