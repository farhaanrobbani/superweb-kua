<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_pria', 'asal_pria', 'nama_wanita', 'asal_wanita', 'tanggal_akad', 'tempat_nikah', 'active'])]
class MarriageAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'marriage_announcements';

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('active', true)
            ->whereDate('tanggal_akad', '>=', today())
            ->orderBy('tanggal_akad')
            ->orderBy('id');
    }

    public function isBerlalu(): bool
    {
        return $this->tanggal_akad->isPast() && ! $this->tanggal_akad->isToday();
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'tanggal_akad' => 'date',
        ];
    }
}
