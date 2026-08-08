<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tanggal',
    'data',
    'created_by',
])]
class KuaDailyData extends Model
{
    protected $casts = [
        'data' => 'array',
        'created_by' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function value(string $key): ?int
    {
        $value = $this->data[$key] ?? null;

        return is_numeric($value) ? (int) $value : null;
    }

    public function totalVolume(): int
    {
        return array_sum(array_map('intval', $this->data ?? []));
    }
}
