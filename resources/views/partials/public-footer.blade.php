<footer class="bg-teal-900 text-teal-50">
    @php
        $sosmeds = collect([
            ['label' => 'Instagram', 'url' => kua_setting('sosmed_instagram'), 'platform' => 'instagram'],
            ['label' => 'TikTok', 'url' => kua_setting('sosmed_tiktok'), 'platform' => 'tiktok'],
            ['label' => 'WhatsApp', 'url' => kua_setting('sosmed_whatsapp'), 'platform' => 'whatsapp'],
        ])->filter(fn ($s) => ! empty($s['url']))->values()->all();

        $linkTerkaits = collect(json_decode(kua_setting('link_terkait', '[]'), true) ?? [])
            ->filter(fn ($l) => ! empty($l['url']) && ! empty($l['label']))
            ->values()
            ->all();
    @endphp

    <div class="mx-auto max-w-5xl px-6 py-10">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Profil Instansi --}}
            <div class="rounded-xl bg-teal-800/40 p-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />
                    </svg>
                    {{ kua_setting('instansi', 'Kantor Urusan Agama') }}
                </h3>
                <p class="mt-3 text-sm leading-relaxed text-teal-100/70">
                    @if (kua_setting('kecamatan'))
                        Kecamatan {{ kua_setting('kecamatan') }}
                        {{ kua_setting('kabupaten') ? ', '.kua_setting('kabupaten') : '' }}
                    @endif
                    {{ kua_setting('kode_pos') ? '('.kua_setting('kode_pos').')' : '' }}
                </p>
            </div>

            {{-- Kontak --}}
            <div class="rounded-xl bg-teal-800/40 p-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.436-4.136-7.032-7.032l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    Kontak
                </h3>
                <ul class="mt-3 space-y-1.5 text-sm leading-relaxed text-teal-100/70">
                    @if (kua_setting('alamat'))
                        <li class="flex gap-2"><span class="shrink-0">Alamat:</span><span>{{ kua_setting('alamat') }}</span></li>
                    @endif
                    @if (kua_setting('telepon'))
                        <li>Telepon: {{ kua_setting('telepon') }}</li>
                    @endif
                    @if (kua_setting('email'))
                        <li>Email: <a href="mailto:{{ kua_setting('email') }}" class="transition hover:text-white">{{ kua_setting('email') }}</a></li>
                    @endif
                </ul>
            </div>

            {{-- Jam Layanan --}}
            <div class="rounded-xl bg-teal-800/40 p-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>
                    Jam Layanan
                </h3>
                <p class="mt-3 text-sm leading-relaxed text-teal-100/70">
                    @if (kua_setting('jam_layanan'))
                        {!! nl2br(e(kua_setting('jam_layanan'))) !!}
                    @else
                        Senin – Jumat<br>08.00 – 15.00 WIB
                    @endif
                </p>
            </div>

            {{-- Media Sosial --}}
            @if ($sosmeds)
                <div class="rounded-xl bg-teal-800/40 p-5">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3h9a4.5 4.5 0 014.5 4.5v9a4.5 4.5 0 01-4.5 4.5h-9A4.5 4.5 0 013 16.5v-9A4.5 4.5 0 017.5 3zM15.75 11.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM17.25 6h.008v.008H17.25V6z" />
                        </svg>
                        Media Sosial
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm text-teal-100/70">
                        @foreach ($sosmeds as $sosmed)
                            <li>
                                <a href="{{ $sosmed['url'] }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 transition hover:text-white">
                                    @include('partials.sosmed-icon', ['platform' => $sosmed['platform'], 'class' => 'h-4 w-4 shrink-0'])
                                    {{ $sosmed['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Link Terkait --}}
        @if ($linkTerkaits)
            <div class="mt-8 rounded-xl bg-teal-800/40 p-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                    Link Terkait
                </h3>
                <ul class="mt-3 grid grid-cols-1 gap-x-6 gap-y-2 text-sm text-teal-100/70 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($linkTerkaits as $link)
                        <li>
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 transition hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="border-t border-teal-800/60 py-4 text-center text-xs text-teal-200/60">
        &copy; {{ date('Y') }} {{ kua_setting('instansi', 'Kantor Urusan Agama') }}. Seluruh hak cipta dilindungi.
    </div>
</footer>
