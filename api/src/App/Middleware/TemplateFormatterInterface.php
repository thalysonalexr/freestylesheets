<?php

namespace App\Middleware;

use Zend\Expressive\Twig\TwigRenderer;

interface TemplateFormatterInterface
{
    public function __construct(TwigRenderer $template);
}
