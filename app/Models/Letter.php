<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'letter_type_id',
    'nomor',
    'tanggal_surat',
    'perihal',
    'data',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'keterangan',
])]
class Letter extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_TERBIT = 'terbit';
    public const STATUS_DITOLAK = 'ditolak';

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_DIAJUKAN => 'Diajukan',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_TERBIT => 'Terbit',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'tanggal_surat' => 'date',
            'approved_at' => 'datetime',
        ];
    }
}
