<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat {{ $letter->nomor }}</title>
    <style>
        @page { margin: 3cm 3cm 3cm 4cm; }
        * { font-family: 'Arial', 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; line-height: 1.5; color: #111; }

        /* Kop surat */
        .kop { text-align: center; margin-bottom: 4px; }
        .kop .instansi, .kop-dengan-logo .instansi, .kop .judul, .kop-dengan-logo .judul { font-size: {{ $kopSizes['judul'] }}px; font-weight: bold; letter-spacing: 0.5px; }
        .kop .sub, .kop-dengan-logo .sub { font-size: {{ $kopSizes['sub'] }}px; font-weight: bold; }
        .kop .alamat, .kop-dengan-logo .alamat, .kop .baris, .kop-dengan-logo .baris { font-size: {{ $kopSizes['baris'] }}px; }
        .kop-dengan-logo { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .kop-dengan-logo td { vertical-align: middle; }
        .kop-dengan-logo .logo { width: 100px; text-align: center; }
        .kop-dengan-logo .logo img { width: 90px; height: auto; }
        .kop-dengan-logo .teks { text-align: center; padding-left: 6px; }
        .garis-tebal { border: none; border-top: 3px solid #111; margin: 4px 0 0 0; }
        .garis-tipis { border: none; border-top: 1.5px solid #111; margin: 1px 0 0 0; }

        .meta { margin: 18px 0; }
        .meta div { display: block; margin-bottom: 2px; }
        .meta .baris { padding-left: 4.5em; text-indent: -4.5em; }
        .meta .nilai { display: inline-block; width: 2.2em; text-align: center; }
        .isi { text-align: justify; }
        .isi p { margin: 0 0 12px 0; }
        .ttd { margin-top: 40px; text-align: right; padding-right: 8px; }
        .ttd .kota { margin-bottom: 80px; }
        .ttd .ttd-img { height: 75px; width: auto; margin-bottom: 4px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }
        .ttd .nip { font-size: 11px; }
    </style>
</head>
<body>
    @php($selectedLogo = ($settings['kop_logo'] ?? 'logo1') === 'logo2' && ! empty($settings['logo2_path']) ? $settings['logo2_path'] : $settings['logo_path'])
    @php($hasLogo = ! empty($selectedLogo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($selectedLogo))
    @php($hasKopTeks = ! empty($kopLines))

    @if ($hasLogo || $hasKopTeks)
        <table class="kop-dengan-logo">
            <tr>
                @if ($hasLogo)
                    <td class="logo">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->path($selectedLogo) }}">
                    </td>
                @endif
                <td class="teks">
                    @if ($hasKopTeks)
                        @foreach ($kopLines as $kopLine)
                            <div class="{{ $kopLine['class'] }}">{{ $kopLine['text'] }}</div>
                        @endforeach
                    @else
                        <div class="instansi">{{ $settings['instansi'] }}</div>
                        <div class="sub">KECAMATAN {{ $settings['kecamatan'] }} KABUPATEN {{ $settings['kabupaten'] }}</div>
                        <div class="alamat">
                            {{ $settings['alamat'] }}
                            @if ($settings['telepon']) &bull; Telp. {{ $settings['telepon'] }} @endif
                            @if ($settings['email']) &bull; Email: {{ $settings['email'] }} @endif
                            @if ($settings['kode_pos']) &bull; Kode Pos {{ $settings['kode_pos'] }} @endif
                        </div>
                    @endif
                </td>
            </tr>
        </table>
        <hr class="garis-tebal">
        <hr class="garis-tipis">
    @else
    <div class="kop">
        @if ($hasKopTeks)
            @foreach ($kopLines as $kopLine)
                <div class="{{ $kopLine['class'] }}">{{ $kopLine['text'] }}</div>
            @endforeach
        @else
            <div class="instansi">{{ $settings['instansi'] }}</div>
            <div class="sub">KECAMATAN {{ $settings['kecamatan'] }} KABUPATEN {{ $settings['kabupaten'] }}</div>
            <div class="alamat">
                {{ $settings['alamat'] }}
                @if ($settings['telepon']) &bull; Telp. {{ $settings['telepon'] }} @endif
                @if ($settings['email']) &bull; Email: {{ $settings['email'] }} @endif
                @if ($settings['kode_pos']) &bull; Kode Pos {{ $settings['kode_pos'] }} @endif
            </div>
        @endif
    </div>
    <hr class="garis-tebal">
    <hr class="garis-tipis">
    @endif

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
        <div class="kota">{{ Str::title($settings['kabupaten']) }}, {{ $letter->tanggal_surat ? tanggal_indonesia($letter->tanggal_surat, 'd F Y') : '' }}</div>
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
