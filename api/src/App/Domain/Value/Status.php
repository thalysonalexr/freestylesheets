<?php

declare(strict_types=1);

namespace App\Domain\Value;

use MabeEnum\Enum;

final class Status extends Enum
{
    const APPROVED = true;
    const NOT_APPROVED = false;
}
