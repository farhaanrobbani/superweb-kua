<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['nama', 'nip', 'kontak', 'jabatan', 'pangkat_golongan', 'bagian', 'foto', 'sort_order', 'active'])]
class Staff extends Model
{
    use HasFactory;

    public function fotoUrl(): ?string
    {
        return $this->foto && Storage::disk('public')->exists($this->foto)
            ? Storage::disk('public')->url($this->foto)
            : null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('nama');
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
