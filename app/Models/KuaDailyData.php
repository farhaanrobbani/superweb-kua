<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tanggal',
    'pendaftaran_nikah_kantor',
    'pendaftaran_nikah_luar_kantor',
    'pelaksanaan_nikah_kantor',
    'pelaksanaan_nikah_luar_kantor',
    'pelaksanaan_bimwin',
    'duplikat_buku_nikah',
    'surat_rekomendasi_nikah',
    'legalisir_buku_nikah',
    'surat_keluar',
    'pelaksanaan_wakaf',
    'created_by',
])]
class KuaDailyData extends Model
{
    public const ACTIVITY_COLUMNS = [
        'pendaftaran_nikah_kantor' => 'Pendaftaran Nikah di Kantor',
        'pendaftaran_nikah_luar_kantor' => 'Pendaftaran Nikah di Luar Kantor',
        'pelaksanaan_nikah_kantor' => 'Pelaksanaan Nikah di Kantor',
        'pelaksanaan_nikah_luar_kantor' => 'Pelaksanaan Nikah di Luar Kantor',
        'pelaksanaan_bimwin' => 'Pelaksanaan Bimbingan Perkawinan (Bimwin)',
        'duplikat_buku_nikah' => 'Pelayanan Duplikat Buku Nikah',
        'surat_rekomendasi_nikah' => 'Penerbitan Surat Rekomendasi Nikah',
        'legalisir_buku_nikah' => 'Pelayanan Legalisir Buku Nikah',
        'surat_keluar' => 'Pengelolaan & Pengiriman Surat Keluar',
        'pelaksanaan_wakaf' => 'Pelaksanaan & Pelayanan Akta Wakaf',
    ];

    protected $casts = [
        'created_by' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function totalVolume(): int
    {
        $total = 0;

        foreach (array_keys(self::ACTIVITY_COLUMNS) as $column) {
            $total += (int) $this->{$column};
        }

        return $total;
    }
}
