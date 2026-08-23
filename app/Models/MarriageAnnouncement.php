<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['no_pendaftaran', 'nama_pria', 'bin_pria', 'alamat_pria', 'nama_wanita', 'binti_wanita', 'alamat_wanita', 'tanggal_akad', 'tempat_nikah', 'status_wali', 'active'])]
class MarriageAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'marriage_announcements';

    public function namaLengkapPria(): string
    {
        return $this->bin_pria
            ? $this->nama_pria.' bin '.$this->bin_pria
            : $this->nama_pria;
    }

    public function namaLengkapWanita(): string
    {
        return $this->binti_wanita
            ? $this->nama_wanita.' binti '.$this->binti_wanita
            : $this->nama_wanita;
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('active', true)
            ->whereDate('tanggal_akad', '>=', today())
            ->orderBy('tanggal_akad')
            ->orderBy('id');
    }

    public function scopeBerlalu(Builder $query): Builder
    {
        return $query->where('active', true)
            ->whereDate('tanggal_akad', '<', today())
            ->orderByDesc('tanggal_akad')
            ->orderByDesc('id');
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
