<?php

declare(strict_types=1);

namespace App\Middleware\InputFilter;

use Zend\InputFilter\InputFilter;

interface InputFilterInterface
{
    public function __construct(InputFilter $filters);
}
