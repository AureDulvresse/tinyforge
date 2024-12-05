<?php

namespace Forge\Support;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LinkExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('link', [$this, 'renderLink'], ['is_safe' => ['html']])
        ];
    }

    public function renderLink(string $uri, $text = "", $class = ""): string
    {
        return "<a href=\"$uri\" class=\"$class\">$text</a>";
    }
}
