<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'label', 'active', 'sort_order'])]
class KuaActivityTheme extends Model
{
    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function activeList(): array
    {
        return static::query()
            ->active()
            ->ordered()
            ->pluck('label', 'key')
            ->all();
    }

    public static function labelOf(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        $label = static::query()->where('key', $key)->value('label');

        return $label ?: $key;
    }
}
