<?php

namespace App\Models\Enums;

enum TaskStatuses: int
{
    case PENDING = 0;
    case ASSIGNED = 1;
    case DELAYED = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
