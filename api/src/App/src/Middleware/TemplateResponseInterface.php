<?php

namespace App\Middleware;

use Zend\Expressive\Twig\TwigRenderer;

interface TemplateResponseInterface
{
    public function __construct(TwigRenderer $template);
}
