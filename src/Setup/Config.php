<?php

namespace Forge\Config;

class Config
{
    /**
     * Charge un fichier de configuration et retourne son contenu.
     *
     * @param string $file Nom du fichier de configuration (sans extension).
     * @return array Contenu de la configuration.
     * @throws \Exception Si le fichier est introuvable.
     */
    public static function load(string $file): array
    {
        $path = __DIR__ . "/{$file}.php";

        if (!file_exists($path)) {
            throw new \Exception("Le fichier de configuration '{$file}.php' est introuvable.");
        }

        return include $path;
    }

    /**
     * Récupère une valeur d'un fichier de configuration.
     *
     * @param string $file Nom du fichier de configuration (sans extension).
     * @param string|null $key Clé spécifique à récupérer (facultatif).
     * @param mixed $default Valeur par défaut si la clé n'existe pas.
     * @return mixed La valeur de configuration ou tout le fichier si $key est null.
     */
    public static function get(string $file, ?string $key = null, $default = null)
    {
        $config = self::load($file);

        if ($key === null) {
            return $config;
        }

        return $config[$key] ?? $default;
    }
}
