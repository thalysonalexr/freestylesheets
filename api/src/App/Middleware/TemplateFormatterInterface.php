<?php

namespace App\Middleware;

use Zend\Expressive\Template\TemplateRendererInterface;

interface TemplateFormatterInterface
{
    public function __construct(TemplateRendererInterface $template);
}
