<?php

namespace App\Enums;

enum ProjectTaskStatus: string
{
    case Open = 'open';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'باز',
            self::Done => 'انجام شد',
        };
    }
}
