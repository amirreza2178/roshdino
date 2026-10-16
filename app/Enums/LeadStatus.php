<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Meeting = 'meeting';
    case Proposal = 'proposal';
    case Won = 'won';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::New => 'جدید',
            self::Contacted => 'تماس گرفته شد',
            self::Meeting => 'جلسه',
            self::Proposal => 'پیشنهاد',
            self::Won => 'فروش شد',
            self::Lost => 'از دست رفت',
        };
    }
}
