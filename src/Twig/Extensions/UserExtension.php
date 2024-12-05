<?php

namespace Forge\Support;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_logged_in', [$this, 'isLoggedIn']),
        ];
    }

    public function isLoggedIn(): bool
    {
        // Vérifier si l'utilisateur est connecté
        return isset($_SESSION['user_id']);
    }
}
