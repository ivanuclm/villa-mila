<x-filament::section :contained="false" class="w-full">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50">Calendario rápido</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $monthLabel }} · Resumen visual de estancias</p>
        </div>
    </div>

    <div class="grid grid-cols-7 gap-px overflow-hidden rounded-xl border border-gray-200 bg-gray-200 text-center text-xs shadow-sm dark:border-gray-700 dark:bg-gray-700">
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">L</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">M</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">X</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">J</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">V</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">S</div>
        <div class="bg-gray-50 px-1 py-2 font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-100">D</div>

        @foreach ($weeks as $week)
            @foreach ($week as $day)
                <div class="bg-white px-2 py-2 text-gray-900 dark:bg-gray-900 dark:text-gray-100 {{ $day['inMonth'] ? '' : 'opacity-40' }}">
                    <div class="flex items-center justify-between text-sm font-semibold">
                        <span>{{ $day['date']->day }}</span>
                        @if ($day['bookings'])
                            <span class="text-[10px] font-semibold text-gray-500">{{ count($day['bookings']) }}</span>
                        @endif
                    </div>
                    <div class="mt-2 space-y-1">
                        @foreach ($day['bookings'] as $booking)
                            @php
                                $color = match ($booking['status']) {
                                    'pending' => 'bg-amber-100 text-amber-900 ring-amber-200',
                                    'hold' => 'bg-sky-100 text-sky-900 ring-sky-200',
                                    'confirmed' => 'bg-emerald-100 text-emerald-900 ring-emerald-200',
                                    'cancelled' => 'bg-rose-100 text-rose-900 ring-rose-200',
                                    default => 'bg-gray-100 text-gray-900 ring-gray-200',
                                };
                            @endphp
                            <div class="rounded-lg px-2 py-1 text-left text-[11px] font-medium ring-1 ring-inset {{ $color }}">
                                <div class="flex items-center justify-between">
                                    <span class="truncate">{{ $booking['name'] }}</span>
                                    <span class="text-[10px] uppercase">{{ $booking['label'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</x-filament::section>
