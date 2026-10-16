<?php

namespace App\Enums;

enum LeadServiceInterest: string
{
    case Web = 'web';
    case Software = 'software';
    case Growth = 'growth';
    case Unsure = 'unsure';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'طراحی و توسعه وب',
            self::Software => 'نرم‌افزار اختصاصی',
            self::Growth => 'راهکارهای رشد دیجیتال',
            self::Unsure => 'هنوز مطمئن نیستم',
        };
    }
}
