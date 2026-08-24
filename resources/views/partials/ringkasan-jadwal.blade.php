@php
    $title = $title ?? 'Ringkasan Jadwal';
    $grouped = $announcements->groupBy(fn ($a) => $a->tanggal_akad->format('Y-m-d'))->take(6);
@endphp
<div class="overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-teal-100 bg-teal-50/60 px-5 py-3">
        <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $title }}</p>
        <span class="rounded-full bg-teal-700 px-2.5 py-0.5 text-xs font-semibold text-white">{{ $announcements->count() }} pasangan</span>
    </div>
    <div class="grid grid-cols-1 gap-6 px-5 py-6 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3 lg:gap-6">
        @foreach ($grouped as $date => $items)
            <div class="flex items-center gap-3 rounded-lg border border-teal-50 bg-teal-50/40 p-4 transition hover:border-teal-200 hover:bg-teal-50/70">
                <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-md bg-teal-700 text-white">
                    <span class="text-base leading-none font-bold">{{ \Illuminate\Support\Carbon::parse($date)->day }}</span>
                    <span class="text-[10px] uppercase leading-tight">{{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('M') }}</span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-teal-900">{{ tanggal_indonesia($date, 'l, d F Y') }}</p>
                    <p class="text-xs text-[#1b1b1870]">{{ $items->count() }} peristiwa</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
