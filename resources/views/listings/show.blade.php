<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>{{ $listing['name'] }} – Reserva</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- Quitar Litepicker:
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js" defer></script>
--}}

{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- en <head>, debajo de flatpickr.min.js -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

{{-- Alpine --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

{{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
<style>
  /* 1) Que el ancho incluya padding y borde en TODO */
  *, *::before, *::after { box-sizing: border-box; }

  body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu; padding: 2rem; }
  .card { max-width: 900px; margin: 0 auto; background: #111; color: #eee; border-radius: 16px; padding: 1.5rem; }

  /* 2) Grid responsivo */
  .row {
    display: grid;
    gap: 1rem;
    grid-template-columns: 1fr 1fr;
  }
  @media (max-width: 820px) {
    .row { grid-template-columns: 1fr; }
  }

  /* 3) Sub-tarjetas */
  .row > div { background: #1b1b1b; border-radius: 12px; padding: 1rem; }

  label { display:block; font-size: .9rem; opacity:.8; margin-bottom:.25rem; }

  /* 4) Inputs y selects no se salen (gracias al box-sizing) */
  input, select {
    width: 100%;
    padding: .6rem .7rem;
    border-radius: 10px;
    border: 1px solid #333;
    background: #0f0f0f;
    color: #fff;
  }

  /* 5) Botón alineado y sin “saltos” de ancho */
  button {
    width: 100%;
    padding: .7rem 1rem;
    border-radius: 10px;
    background: #6366f1;
    border: 0;
    color: #fff;
    cursor: pointer;
  }

  .muted { opacity:.7; font-size:.9rem; }

  /* ---- Flatpickr: precio por día ---- */
  .flatpickr-day {
    position: relative;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    line-height: 1.1; height: 60px; border-radius: 8px;
  }
  .flatpickr-day .price-tag{
    position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
    font-size: .62rem; padding: 2px 6px; border-radius: 999px;
    background: #23232366; border: 1px solid #333; color: #e6e6e6;
    pointer-events: none; white-space: nowrap;
  }
  .flatpickr-day.unavailable{ background:#2a2a2acc !important; color:#777 !important; cursor:not-allowed; }
  .flatpickr-day.selected .price-tag,
  .flatpickr-day.startRange .price-tag,
  .flatpickr-day.endRange .price-tag { background: rgba(0,0,0,.25); border-color: rgba(255,255,255,.35); }
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
          <input type="number" min="1" :max="maxGuests" x-model.number="guests" @change="refreshPrices()">
        </div>
        <div>
          <label>&nbsp;</label>
          <button @click="doQuote()" :disabled="!arrival || !departure">Calcular precio</button>
        </div>
      </div>
      <p class="muted" style="margin-top:.5rem">Capacidad máxima: {{ $listing['max_guests'] }}</p>
      <p class="muted" x-show="error" x-text="error" style="color:#fca5a5"></p>
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
    priceMap: {},
    error: null,
    fpInstance: null,

    async init() {
      // Fechas no disponibles
      const res = await fetch(`/api/listings/${this.listingSlug}/unavailable-dates`);
      const data = res.ok ? await res.json() : { unavailable: [] };
      this.unavailable = data.unavailable ?? [];

      const input = document.getElementById('daterange');

      const fp = flatpickr(input, {
        locale: 'es',
        mode: "range",
        dateFormat: "d/m/Y",
        minDate: "today",
        disable: this.unavailable,   // deshabilita ocupados
        showMonths: 2,
        onDayCreate: (dObj, dStr, inst, dayElem) => {
          const date = dayElem.dateObj;
          const y = date.getFullYear();
          const m = String(date.getMonth()+1).padStart(2,'0');
          const d = String(date.getDate()).padStart(2,'0');
          const key = `${y}-${m}-${d}`;

          if (this.unavailable.includes(key)) {
            dayElem.classList.add('unavailable');
            return;
          }

          const price = this.priceMap[key];
          if (price != null) {
            const span = document.createElement('span');
            span.className = 'price-tag';
            span.textContent = `€${Number(price).toFixed(0)}`;
            dayElem.appendChild(span);
          }
        },
        onReady: (selectedDates, dateStr, inst) => {
          this.fpInstance = inst;
          this.fetchMonthPrices(inst);
        },
        onMonthChange: (selectedDates, dateStr, inst) => {
          this.fetchMonthPrices(inst);
        },
        onChange: (selectedDates) => {
          this.error = null;
          this.arrival   = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : null;
          this.departure = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : null;
          this.quote = null;
        },
      });
    },

    async fetchMonthPrices(inst) {
      // primer mes visible
      const year  = inst.currentYear;
      const month = inst.currentMonth + 1; // 0-based→1..12
      const months = [`${year}-${String(month).padStart(2,'0')}`];

      // si hay 2 meses visibles, también el siguiente
      if (inst.config.showMonths > 1) {
        const next = new Date(year, inst.currentMonth + 1, 1);
        months.push(`${next.getFullYear()}-${String(next.getMonth()+1).padStart(2,'0')}`);
      }

      for (const ym of months) {
        const url = `/api/listings/${this.listingSlug}/prices?month=${ym}&guests=${this.guests}`;
        const r = await fetch(url);
        if (!r.ok) { this.error = 'No se pudieron cargar precios.'; continue; }
        const { prices } = await r.json();
        Object.assign(this.priceMap, prices);
      }

      inst.redraw(); // repinta celdas con los precios
    },

    async refreshPrices() {
      if (!this.fpInstance) return;
      // Limpia precios y vuelve a pedir para el/los meses visibles
      this.priceMap = {};
      await this.fetchMonthPrices(this.fpInstance);
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
      if (!res.ok) { this.error = 'No se pudo calcular el precio.'; return; }
      this.quote = await res.json();
    },
  }
}
document.addEventListener('alpine:init', () => {});
</script>
</body>
</html>
