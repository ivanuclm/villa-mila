<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $listing['name'] }} – Reserva</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Litepicker (date-range) desde CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js" defer></script>

    {{-- Alpine para interactividad mínima --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu; padding: 2rem; }
        .card { max-width: 900px; margin: 0 auto; background: #111; color: #eee; border-radius: 16px; padding: 1.5rem; }
        .row { display: grid; gap: 1rem; grid-template-columns: 1fr 1fr; }
        .row > div { background: #1b1b1b; border-radius: 12px; padding: 1rem; }
        label { display:block; font-size: .9rem; opacity:.8; margin-bottom:.25rem; }
        input, select { width:100%; padding:.6rem .7rem; border-radius:10px; border:1px solid #333; background:#0f0f0f; color:#fff; }
        button { padding:.7rem 1rem; border-radius:10px; background:#6366f1; border:0; color:#fff; cursor:pointer; }
        .muted { opacity:.7; font-size:.9rem; }
    </style>
</head>
<body>
<div class="card" x-data="bookingWidget()" x-init="init()">
    <h1 style="margin:0 0 .25rem 0">{{ $listing['name'] }}</h1>
    <p class="muted">{{ $listing['address'] }} — Licencia: {{ $listing['license_number'] ?? '—' }}</p>

    <div class="row" style="margin-top:1rem">
        <div>
            <h3 style="margin-top:0">Selecciona fechas</h3>
            <label>Rango de fechas</label>
            <input id="daterange" placeholder="Llegada — Salida" readonly>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:.75rem; margin-top:.75rem">
                <div>
                    <label>Huéspedes</label>
                    <input type="number" min="1" :max="maxGuests" x-model.number="guests">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button @click="doQuote()" :disabled="!arrival || !departure">Calcular precio</button>
                </div>
            </div>
            <p class="muted" style="margin-top:.5rem">Capacidad máxima: {{ $listing['max_guests'] }}</p>
        </div>

        <div>
            <h3 style="margin-top:0">Resumen</h3>
            <template x-if="quote">
                <div>
                    <p><strong>Noche(s):</strong> <span x-text="quote.nights"></span></p>
                    <p><strong>Subtotal:</strong> €<span x-text="quote.subtotal.toFixed(2)"></span></p>
                    <p><strong>Limpieza:</strong> €<span x-text="quote.cleaning.toFixed(2)"></span></p>
                    <hr>
                    <p><strong>Total:</strong> €<span x-text="quote.total.toFixed(2)"></span></p>
                </div>
            </template>
            <template x-if="!quote">
                <p class="muted">Selecciona fechas y pulsa “Calcular precio”.</p>
            </template>
        </div>
    </div>
</div>

<script>
function bookingWidget() {
    return {
        listingSlug: @js($listing['slug']),
        arrival: null,
        departure: null,
        guests: 2,
        maxGuests: @js($listing['max_guests']),
        quote: null,
        unavailable: [],

        // Helper para formatear YYYY-MM-DD
        formatYMD(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        },

            // Itera cada día [start, end) (noche a noche)
        rangeHasLocked(startStr, endStr) {
            const locked = new Set(this.unavailable); // dias bloqueados "YYYY-MM-DD"
            const start = new Date(startStr);
            const end = new Date(endStr);
            for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
                if (locked.has(this.formatYMD(d))) return true;
            }
            return false;
        },

        async init() {
            // 1) Carga días ocupados
            const res = await fetch(`/api/listings/${this.listingSlug}/unavailable-dates`);
            if (!res.ok) { console.error('API unavailable-dates', res.status); return; }
            const data = await res.json();
            this.unavailable = data.unavailable ?? [];

            const input = document.getElementById('daterange');

            // 2) Inicia Litepicker
           const picker = new Litepicker({
                element: input,
                singleMode: false,
                numberOfMonths: 2,
                numberOfColumns: 2,
                format: 'YYYY-MM-DD',
                lockDays: this.unavailable,
                minDate: new Date(),       // ⬅️ bloquea pasado
                autoApply: true,
                // splitView: true,
                // dropdowns: {
                //     minYear: new Date().getFullYear(),
                //     maxYear: new Date().getFullYear() + 5,
                //     // months: true,
                //     years: true,
                // },
                setup: (picker) => {
                    picker.on('selected', (date1, date2) => {
                    this.arrival   = date1 ? date1.format('YYYY-MM-DD') : null;
                    this.departure = date2 ? date2.format('YYYY-MM-DD') : null;

                    // Validación: no permitir rangos que contengan bloqueados
                    if (this.arrival && this.departure) {
                        if (this.rangeHasLocked(this.arrival, this.departure)) {
                        // Cancela y avisa
                        picker.clearSelection();
                        this.arrival = this.departure = null;
                        this.quote = null;
                        alert('Ese rango contiene días no disponibles. Por favor elige otro rango.');
                        return;
                        }
                    }

                    // Mantén selección sin parpadeo visual
                    if (this.arrival && this.departure) {
                        picker.setDateRange(this.arrival, this.departure, true);
                    }

                    this.quote = null;
                    });

                    picker.on('hide', () => {
                    if (this.arrival && this.departure) {
                        picker.setDateRange(this.arrival, this.departure, true);
                    }
                    });
                },
            });

            // (Opcional) Si ya tenías valores previos en this.arrival/departure, inicialízalos:
            if (this.arrival && this.departure) {
                picker.setDateRange(this.arrival, this.departure, true);
            }
        },

        async doQuote() {
            if (!this.arrival || !this.departure) return;
            const res = await fetch(`/api/listings/${this.listingSlug}/quote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    arrival: this.arrival,
                    departure: this.departure,
                    guests: this.guests,
                }),
            });
            this.quote = await res.json();
        }
    }
}
document.addEventListener('alpine:init', () => {});
</script>
</body>
</html>
