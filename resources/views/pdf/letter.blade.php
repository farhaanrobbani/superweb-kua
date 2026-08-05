<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat {{ $letter->nomor }}</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; line-height: 1.6; color: #111; }
        .kop { text-align: center; margin-bottom: 4px; }
        .kop .instansi { font-size: 17px; font-weight: bold; letter-spacing: 0.5px; }
        .kop .sub { font-size: 13px; font-weight: bold; }
        .kop .alamat { font-size: 10.5px; }
        .kop hr { border: none; border-top: 3px solid #111; margin: 6px 0 2px 0; }
        .kop .garis-bawah { border: none; border-top: 1.5px solid #111; }
        .meta { margin: 18px 0; }
        .meta div { display: block; margin-bottom: 2px; }
        .meta .baris { padding-left: 4.5em; text-indent: -4.5em; }
        .meta .nilai { display: inline-block; width: 2.2em; text-align: center; }
        .isi { text-align: justify; }
        .isi p { margin: 0 0 12px 0; }
        .ttd { margin-top: 40px; text-align: right; padding-right: 8px; }
        .ttd .kota { margin-bottom: 80px; }
        .ttd .ttd-img { height: 75px; margin-bottom: 4px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }
        .ttd .nip { font-size: 11px; }
        .font-mono { font-family: 'DejaVu Sans', monospace; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="instansi">{{ $settings['instansi'] }}</div>
        <div class="sub">KECAMATAN {{ $settings['kecamatan'] }} KABUPATEN {{ $settings['kabupaten'] }}</div>
        <div class="alamat">
            {{ $settings['alamat'] }}
            @if ($settings['telepon']) &bull; Telp. {{ $settings['telepon'] }} @endif
            @if ($settings['email']) &bull; Email: {{ $settings['email'] }} @endif
            @if ($settings['kode_pos']) &bull; Kode Pos {{ $settings['kode_pos'] }} @endif
        </div>
        <hr>
        <hr class="garis-bawah">
    </div>

    <div class="meta">
        <div class="baris">Nomor<span class="nilai">:</span>{{ $letter->nomor }}</div>
        <div class="baris">Lampiran<span class="nilai">:</span>-</div>
        <div class="baris">Perihal<span class="nilai">:</span>{{ $letter->perihal }}</div>
    </div>

    <div style="text-align: right;">{{ $letter->tanggal_surat ? tanggal_indonesia($letter->tanggal_surat, 'd F Y') : '' }}</div>

    <div class="isi">
        {!! nl2br(e($body)) !!}
    </div>

    <div class="ttd">
        <div class="kota">{{ $settings['kabupaten'] }}, {{ $letter->tanggal_surat ? tanggal_indonesia($letter->tanggal_surat, 'd F Y') : '' }}</div>
        <div>Kepala {{ $settings['instansi'] }},</div>
        @if ($settings['ttd_path'] && \Illuminate\Support\Facades\Storage::exists($settings['ttd_path']))
            <div>
                <img class="ttd-img" src="{{ \Illuminate\Support\Facades\Storage::path($settings['ttd_path']) }}">
            </div>
        @else
            <div style="height: 75px;"></div>
        @endif
        <div class="nama">{{ $settings['kepala_nama'] }}</div>
        @if ($settings['kepala_nip'])
            <div class="nip">NIP. {{ $settings['kepala_nip'] }}</div>
        @endif
    </div>
</body>
</html>
