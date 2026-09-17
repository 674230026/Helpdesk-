<?php

namespace App\Enums;

enum TicketPriority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'ต่ำ',
            self::NORMAL => 'ปกติ',
            self::HIGH => 'สูง',
            self::URGENT => 'ด่วนที่สุด',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-slate-100 text-slate-700 border border-slate-200',
            self::NORMAL => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::HIGH => 'bg-orange-100 text-orange-800 border border-orange-200 font-semibold',
            self::URGENT => 'bg-rose-100 text-rose-800 border border-rose-200 animate-pulse font-bold',
        };
    }

    public function slaHours(): int
    {
        return match ($this) {
            self::URGENT => 4,
            self::HIGH => 8,
            self::NORMAL => 24,
            self::LOW => 48,
        };
    }

    public function sortWeight(): int
    {
        return match ($this) {
            self::URGENT => 4,
            self::HIGH => 3,
            self::NORMAL => 2,
            self::LOW => 1,
        };
    }
}
