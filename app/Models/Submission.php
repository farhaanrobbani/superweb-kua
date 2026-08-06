<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['letter_type_id', 'nama_pemohon', 'kontak', 'data', 'status', 'catatan'])]
class Submission extends Model
{
    use HasFactory;
    public const STATUS_BARU = 'baru';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';

    public static function statuses(): array
    {
        return [
            self::STATUS_BARU => 'Baru',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    public function renderPermohonanBody(): string
    {
        $narrative = $this->letterType->permohonan_body;

        if (blank($narrative)) {
            $narrative = 'Memohon agar diterbitkan ' . $this->letterType->name
                . " yang saya perlukan untuk kepentingan yang sah.\n\n"
                . 'Demikian Surat Permohonan ini kami buat dengan sebenar-benarnya agar dapat menjadi maklum.';
        }

        $values = [];
        foreach ($this->letterType->fields ?? [] as $field) {
            $values[$field['name']] = $this->data[$field['name']] ?? '';
        }
        $values['nama_pemohon'] = $this->nama_pemohon;
        $values['kontak'] = $this->kontak;

        $body = $narrative;
        foreach ($values as $key => $value) {
            $value = $this->formatNarrativeValue($value);
            $body = str_replace('[' . $key . ']', e((string) $value), $body);
        }

        return $body;
    }

    public function permohonanFields(): array
    {
        $fields = $this->letterType->fields ?? [];
        $selected = $this->letterType->permohonan_fields ?? [];

        if (empty($selected)) {
            return $fields;
        }

        return array_values(array_filter(
            $fields,
            fn (array $field) => in_array($field['name'] ?? '', $selected)
        ));
    }

    private function formatNarrativeValue(mixed $value): mixed
    {
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            try {
                return tanggal_indonesia($value);
            } catch (\Throwable) {
                return $value;
            }
        }

        return $value;
    }

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
