<?php

namespace App\Enums;

enum FollowUpChannel: string
{
    case Call = 'call';
    case Message = 'message';
    case Email = 'email';

    public function label(): string
    {
        return match ($this) {
            self::Call => 'تماس',
            self::Message => 'پیام',
            self::Email => 'ایمیل',
        };
    }
}
