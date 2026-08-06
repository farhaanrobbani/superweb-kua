<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Permohonan {{ $submission->letterType->name }}</title>
    <style>
        @page { margin: 4cm 3cm 3cm 4cm; }
        * { font-family: 'Arial', 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; line-height: 1.6; color: #111; }

        .judul { text-align: center; font-weight: bold; text-decoration: underline; font-size: 12px; margin-bottom: 24px; }

        .isi { text-align: justify; }
        .isi p { margin: 0 0 12px 0; }

        .data-tabel { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .data-tabel td { vertical-align: top; padding: 1px 0; }
        .data-tabel .label { width: 190px; }
        .data-tabel .titik { width: 16px; }

        .ttd { margin-top: 48px; text-align: right; }
        .ttd .blok { display: inline-block; text-align: center; }
        .kotak-materai { width: 96px; height: 48px; border: 2px solid #111; margin: 6px auto; text-align: center; line-height: 48px; font-size: 10px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="judul">{{ strtoupper($submission->letterType->name) }}</div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, saya:</p>

        <table class="data-tabel">
            @foreach ($submission->permohonanFields() as $field)
                <tr>
                    <td class="label">{{ $field['label'] }}</td>
                    <td class="titik">:</td>
                    <td>{{ $submission->identityValue($field['name']) }}</td>
                </tr>
            @endforeach
        </table>

        {!! nl2br(e($body)) !!}
    </div>

    <div class="ttd">
        <div class="blok">
            <div>{{ $kabupaten ? $kabupaten . ', ' : '' }}{{ tanggal_indonesia(now()->toDateString(), 'd F Y') }}</div>
            <div>Hormat saya</div>
            <div class="kotak-materai">MATERAI 10.000</div>
            <div class="nama">{{ $submission->nama_pemohon }}</div>
        </div>
    </div>
</body>
</html>
