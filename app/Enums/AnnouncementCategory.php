<?php

namespace App\Enums;

enum AnnouncementCategory: string
{
    case News = 'news';
    case Article = 'article';
    case Announcement = 'announcement';

    public function label(): string
    {
        return match ($this) {
            self::News => 'Berita',
            self::Article => 'Artikel',
            self::Announcement => 'Pengumuman',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::News => 'bg-teal-100 text-teal-800',
            self::Article => 'bg-emerald-100 text-emerald-800',
            self::Announcement => 'bg-amber-100 text-amber-800',
        };
    }
}
