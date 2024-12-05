<?php

namespace Forge\Services;

class PasswordService
{
    // Hachage du mot de passe
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Vérification du mot de passe
    public static function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
