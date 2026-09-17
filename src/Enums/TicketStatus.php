<?php

namespace App\Enums;

enum TicketStatus: string
{
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
    case ASSIGNED = 'assigned';
    case EN_ROUTE = 'en_route';
    case IN_PROGRESS = 'in_progress';
    case WAITING_PARTS = 'waiting_parts';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'รออนุมัติรับเรื่อง',
            self::APPROVED => 'อนุมัติรับเรื่องแล้ว',
            self::ASSIGNED => 'มอบหมายช่างแล้ว',
            self::EN_ROUTE => 'ช่างกำลังเดินทาง',
            self::IN_PROGRESS => 'กำลังดำเนินการซ่อม',
            self::WAITING_PARTS => 'รออะไหล่หรืออุปกรณ์',
            self::RESOLVED => 'ซ่อมเสร็จแล้ว รอตรวจรับ',
            self::CLOSED => 'ปิดงานสมบูรณ์',
            self::CANCELLED => 'ยกเลิกคำขอ',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'รออนุมัติ',
            self::APPROVED => 'อนุมัติแล้ว',
            self::ASSIGNED => 'มอบหมายแล้ว',
            self::EN_ROUTE => 'กำลังเดินทาง',
            self::IN_PROGRESS => 'กำลังซ่อม',
            self::WAITING_PARTS => 'รออะไหล่',
            self::RESOLVED => 'ซ่อมเสร็จแล้ว',
            self::CLOSED => 'ปิดงานแล้ว',
            self::CANCELLED => 'ยกเลิกแล้ว',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'bg-amber-100 text-amber-800 border border-amber-200',
            self::APPROVED => 'bg-cyan-100 text-cyan-800 border border-cyan-200',
            self::ASSIGNED => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::EN_ROUTE => 'bg-purple-100 text-purple-800 border border-purple-200 animate-pulse',
            self::IN_PROGRESS => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
            self::WAITING_PARTS => 'bg-orange-100 text-orange-800 border border-orange-200 font-semibold',
            self::RESOLVED => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            self::CLOSED => 'bg-slate-100 text-slate-700 border border-slate-300',
            self::CANCELLED => 'bg-rose-100 text-rose-700 border border-rose-200',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'bg-amber-500',
            self::APPROVED => 'bg-cyan-500',
            self::ASSIGNED => 'bg-blue-500',
            self::EN_ROUTE => 'bg-purple-500',
            self::IN_PROGRESS => 'bg-indigo-500',
            self::WAITING_PARTS => 'bg-orange-500',
            self::RESOLVED => 'bg-emerald-500',
            self::CLOSED => 'bg-slate-400',
            self::CANCELLED => 'bg-rose-500',
        };
    }

    public function stepIndex(): int
    {
        return match ($this) {
            self::PENDING_APPROVAL => 1,
            self::APPROVED => 2,
            self::ASSIGNED => 2,
            self::EN_ROUTE => 3,
            self::IN_PROGRESS => 4,
            self::WAITING_PARTS => 4,
            self::RESOLVED => 5,
            self::CLOSED => 6,
            self::CANCELLED => 0,
        };
    }

    public function canCancel(): bool
    {
        return in_array($this, [self::PENDING_APPROVAL, self::APPROVED, self::ASSIGNED], true);
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDING_APPROVAL => [self::APPROVED, self::ASSIGNED, self::CANCELLED],
            self::APPROVED => [self::ASSIGNED, self::CANCELLED],
            self::ASSIGNED => [self::EN_ROUTE, self::IN_PROGRESS, self::ASSIGNED, self::CANCELLED],
            self::EN_ROUTE => [self::IN_PROGRESS, self::WAITING_PARTS, self::CANCELLED],
            self::IN_PROGRESS => [self::WAITING_PARTS, self::RESOLVED],
            self::WAITING_PARTS => [self::IN_PROGRESS, self::RESOLVED],
            self::RESOLVED => [self::CLOSED, self::IN_PROGRESS],
            self::CLOSED => [],
            self::CANCELLED => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
