<?php

namespace App\Enums;

enum ProposalStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'پیش‌نویس',
            self::Sent => 'فرستاده شد',
            self::Accepted => 'پذیرفته شد',
            self::Rejected => 'رد شد',
        };
    }
}
