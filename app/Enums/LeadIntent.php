<?php

namespace App\Enums;

enum LeadIntent: string
{
    case Request = 'request';
    case Contact = 'contact';
    case Meeting = 'meeting';

    public function label(): string
    {
        return match ($this) {
            self::Request => 'درخواست',
            self::Contact => 'راه تماس',
            self::Meeting => 'رزرو جلسه',
        };
    }
}
