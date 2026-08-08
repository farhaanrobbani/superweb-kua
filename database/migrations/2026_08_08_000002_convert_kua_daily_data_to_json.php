<?php

use App\Models\KuaDailyData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
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
        ];

        if (! Schema::hasColumn('kua_daily_data', 'pendaftaran_nikah_kantor')) {
            return;
        }

        Schema::table('kua_daily_data', function (Blueprint $table) {
            $table->json('data')->nullable()->after('tanggal');
        });

        KuaDailyData::query()
            ->select(array_merge(['id'], $columns))
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($columns) {
                foreach ($rows as $row) {
                    $data = [];

                    foreach ($columns as $column) {
                        if (is_numeric($row->{$column})) {
                            $data[$column] = (int) $row->{$column};
                        }
                    }

                    $row->forceFill(['data' => $data])->save();
                }
            });

        Schema::table('kua_daily_data', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $table->dropColumn($column);
            }
        });
    }

    public function down(): void
    {
        $columns = [
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
        ];

        if (Schema::hasColumn('kua_daily_data', 'data')) {
            Schema::table('kua_daily_data', function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->unsignedInteger($column)->default(0)->after('tanggal');
                }
            });
        }
    }
};
