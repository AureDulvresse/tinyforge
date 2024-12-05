<?php

namespace Forge\Support;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Forge\Forms\CSRF;

class CsrfExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('csrf_token', [$this, 'renderCSRFToken'], ['is_safe' => ['html']])
        ];
    }

    public function renderCSRFToken()
    {
        $token = CSRF::generateToken();
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"$token\">";
    }
}
