<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'tanggal', 'kegiatan', 'pekerjaan', 'activity_type_key', 'total_jumlah'])]
class StaffActivity extends Model
{
    protected $casts = [
        'total_jumlah' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityLabel(): ?string
    {
        return KuaDailyData::ACTIVITY_COLUMNS[$this->activity_type_key] ?? null;
    }
}
