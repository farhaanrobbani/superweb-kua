<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'label', 'description', 'url', 'embed_url', 'icon', 'group', 'sort_order', 'active', 'has_submenu'])]
class NavbarItem extends Model
{
    use HasFactory;

    public const GROUP_MAIN = 'main';
    public const GROUP_TENTANG = 'tentang';

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
            'has_submenu' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
