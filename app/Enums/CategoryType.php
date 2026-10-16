<?php

namespace App\Enums;

enum CategoryType: string
{
    case Service = 'service';
    case Work = 'work';

    public function label(): string
    {
        return match ($this) {
            self::Service => 'خدمت',
            self::Work => 'نمونه‌کار',
        };
    }
}
