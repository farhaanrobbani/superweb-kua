<footer class="bg-teal-900 text-teal-50">
    <div class="mx-auto grid max-w-5xl gap-6 px-6 py-10 text-sm sm:grid-cols-3">
        <div>
            <p class="font-semibold">{{ kua_setting('instansi', 'Kantor Urusan Agama') }}</p>
            <p class="mt-2 leading-relaxed text-teal-100/70">
                {{ kua_setting('kecamatan') ? 'Kecamatan '.kua_setting('kecamatan').(kua_setting('kabupaten') ? ', '.kua_setting('kabupaten') : '') : '' }} {{ kua_setting('kode_pos') ? '('. kua_setting('kode_pos') .')' : '' }}
            </p>
        </div>
        <div>
            <p class="font-semibold">Kontak</p>
            <p class="mt-2 leading-relaxed text-teal-100/70">
                @if (kua_setting('alamat')) {{ kua_setting('alamat') }}<br>@endif
                @if (kua_setting('telepon')) Telepon: {{ kua_setting('telepon') }}<br>@endif
                @if (kua_setting('email')) Email: {{ kua_setting('email') }}@endif
            </p>
        </div>
        <div>
            <p class="font-semibold">Jam Layanan</p>
            <p class="mt-2 leading-relaxed text-teal-100/70">
                @if (kua_setting('jam_layanan'))
                    {!! nl2br(e(kua_setting('jam_layanan'))) !!}
                @else
                    Senin – Jumat<br>08.00 – 15.00 WIB
                @endif
            </p>
        </div>
    </div>
    <div class="border-t border-teal-800/60 py-4 text-center text-xs text-teal-200/60">
        &copy; {{ date('Y') }} {{ kua_setting('instansi', 'Kantor Urusan Agama') }}. Seluruh hak cipta dilindungi.
    </div>
</footer>
