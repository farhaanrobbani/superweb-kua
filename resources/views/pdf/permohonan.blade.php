<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Permohonan {{ $submission->letterType->name }}</title>
    <style>
        * { font-family: 'Arial', 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; line-height: 1.6; color: #111; }

        .judul { text-align: center; font-weight: bold; text-decoration: underline; font-size: 15px; margin-bottom: 4px; }
        .subjudul { text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 16px; }

        .tanggal { text-align: right; margin-bottom: 16px; }

        .isi { text-align: justify; }
        .isi p { margin: 0 0 12px 0; }

        .data-tabel { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .data-tabel td { vertical-align: top; padding: 1px 0; }
        .data-tabel .label { width: 190px; }
        .data-tabel .titik { width: 16px; }

        .signature { width: 100%; margin-top: 30px; }
        .signature td { vertical-align: bottom; }
        .signature .materai { width: 42%; }
        .signature .ttd { width: 58%; text-align: center; }
        .kotak-materai { width: 140px; height: 120px; border: 2px solid #111; }
        .label-materai { font-size: 10px; font-style: italic; text-align: center; margin-top: 2px; }
        .ttd .atas { margin-bottom: 12px; }
        .ttd .ruang-ttd { height: 80px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }
        .ttd .kontak { font-size: 11px; }
    </style>
</head>
<body>
    <div class="judul">SURAT PERMOHONAN</div>
    <div class="subjudul">Permohonan {{ $submission->letterType->name }}</div>

    <div class="tanggal">{{ $kabupaten ? $kabupaten . ', ' : '' }}{{ tanggal_indonesia(now()->toDateString(), 'd F Y') }}</div>

    <div class="isi">
        <p>Saya yang bertanda tangan di bawah ini:</p>

        <table class="data-tabel">
            <tr><td class="label">Nama</td><td class="titik">:</td><td>{{ $submission->nama_pemohon }}</td></tr>
            <tr><td class="label">Kontak</td><td class="titik">:</td><td>{{ $submission->kontak }}</td></tr>
            @foreach ($submission->letterType->fields ?? [] as $field)
                <tr>
                    <td class="label">{{ $field['label'] }}</td>
                    <td class="titik">:</td>
                    <td>{{ $submission->data[$field['name']] ?? '—' }}</td>
                </tr>
            @endforeach
            <tr><td class="label">Jenis Surat yang Dimohon</td><td class="titik">:</td><td>{{ $submission->letterType->name }}</td></tr>
        </table>

        <p>Dengan ini mengajukan permohonan penerbitan <b>{{ $submission->letterType->name }}</b> yang saya perlukan untuk kepentingan yang sah. Demikian surat permohonan ini saya buat dengan sebenar-benarnya dan untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="signature">
        <tr>
            <td class="materai">
                <div class="kotak-materai"></div>
                <div class="label-materai">Materai Rp 10.000</div>
            </td>
            <td class="ttd">
                <div class="atas">{{ $kabupaten ? $kabupaten . ', ' : '' }}{{ tanggal_indonesia(now()->toDateString(), 'd F Y') }}</div>
                <div>Yang membuat permohonan,</div>
                <div class="ruang-ttd"></div>
                <div class="nama">{{ $submission->nama_pemohon }}</div>
                @if ($submission->kontak)
                    <div class="kontak">{{ $submission->kontak }}</div>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
