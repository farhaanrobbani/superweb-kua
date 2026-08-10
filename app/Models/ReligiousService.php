<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'description', 'persyaratan', 'alur', 'sop', 'persyaratan_label', 'alur_label', 'sop_label', 'icon', 'sort_order', 'active'])]
class ReligiousService extends Model
{
    use HasFactory;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
