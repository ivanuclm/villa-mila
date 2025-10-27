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
/* ========= Flatpickr: tema base minimal ========= */
/* Ajusta colores y medidas aquí */
:root{
  /* contenedor */
  --fp-bg:            #f5f5f5ff;
  --fp-border:        #e6e6ea;
  --fp-shadow:        0 10px 30px rgba(0,0,0,.08);
  --fp-radius:        14px;

  /* header y cabeceras */
  --fp-header-bg:     #f7f7f9;
  --fp-header-fg:     #0f172a;
  --fp-weekday-fg:    #6b7280;

  /* día normal */
  --fp-day-bg:        #ffffff;
  --fp-day-fg:        #111827;
  --fp-day-border:    #eef0f3;
  --fp-day-radius:    8px;
  --fp-day-height:    52px;

  /* día fuera de mes / disabled / ocupado */
  --fp-out-bg:        #fafafa;
  --fp-out-fg:        #a1a1aa;

  --fp-disabled-bg:   #f2f3f6;
  --fp-disabled-fg:   #9ca3af;
  --fp-disabled-br:   #eceef2;

  --fp-blocked-bg:    #f1f2f5;
  --fp-blocked-fg:    #9aa0a6;
  --fp-blocked-br:    #e8eaf0;

  /* estados */
  --fp-hover-br:      #d9dee6;
  --fp-accent:        #3b82f6;
  --fp-accent-br:     #2d5dd3;
  --fp-range-bg:      #eaf2ff;
  --fp-range-br:      #d6e4ff;
  --fp-range-day-color-hover: #2d5dd3;
  --fp-range-price-tag-color-hover: #000;
  --fp-range-price-tag-color-hover-start-end: #1e40af;
  --fp-range-bg-price-tag-hover: #d6e4ff;
  --fp-range-br-price-tag-hover: #a9c1ff;
  --fp-range-price-tag-color-hover2: #2d5dd3;

  /* badge de precio */
  --fp-price-bg:      rgba(17,24,39,.06);
  --fp-price-br:      #e5e7eb;
  --fp-price-fg:      #111;
  --fp-position-price-tag-bottom: 6px;
}

/* Contenedor del calendario */
.flatpickr-calendar.fp-base{
  background: var(--fp-bg);
  border: 1px solid var(--fp-border);
  box-shadow: var(--fp-shadow);
  border-radius: var(--fp-radius);
  overflow: hidden;
  color: var(--fp-day-fg);
}

/* Header meses/flechas */
.flatpickr-calendar.fp-base .flatpickr-months{
  background: var(--fp-header-bg);
  border-bottom: 1px solid var(--fp-border);
}
.flatpickr-calendar.fp-base .flatpickr-current-month{
  color: var(--fp-header-fg);
  font-weight: 700;
}

/* Cabecera de días (Lu, Mar...) */
.flatpickr-calendar.fp-base .flatpickr-weekdays{
  background: var(--fp-header-bg);
  border-bottom: 1px solid var(--fp-border);
}
.flatpickr-calendar.fp-base span.flatpickr-weekday{
  color: var(--fp-weekday-fg);
  font-weight: 600;
  letter-spacing: .02em;
}

/* Contenedores de días: SIN padding para mantener 7 columnas */
.flatpickr-calendar.fp-base .flatpickr-days,
.flatpickr-calendar.fp-base .dayContainer{
  background: var(--fp-bg);
  padding: 0;
  border: 0;
}


/* Día: SIN margin; width fijo para 7 columnas; box-sizing para que el borde no rompa */
.flatpickr-calendar.fp-base .flatpickr-day{
  position: relative;
  width: 14.2857% !important;        /* 100/7 */
  height: var(--fp-day-height);
  margin: 0;
  border-radius: var(--fp-day-radius);
  border: 1px solid var(--fp-day-border);
  background: var(--fp-day-bg);
  color: var(--fp-day-fg);
  line-height: 1.1;
  box-sizing: border-box;
}

/* Fuera de mes */
.flatpickr-calendar.fp-base .flatpickr-day.prevMonthDay,
.flatpickr-calendar.fp-base .flatpickr-day.nextMonthDay{
  background: var(--fp-out-bg);
  color: var(--fp-out-fg);
  border-color: var(--fp-day-border);
}

/* Deshabilitados (p.ej. pasado/minDate) */
.flatpickr-calendar.fp-base .flatpickr-day.disabled,
.flatpickr-calendar.fp-base .flatpickr-day.disabled:hover{
  background: var(--fp-disabled-bg) !important;
  color: var(--fp-disabled-fg) !important;
  border-color: var(--fp-disabled-br) !important;
  cursor: not-allowed;
  text-decoration: none;
  opacity: 1;
}

/* Ocupados (tu clase “unavailable”) */
.flatpickr-calendar.fp-base .flatpickr-day.unavailable,
.flatpickr-calendar.fp-base .flatpickr-day.unavailable:hover{
  background: var(--fp-blocked-bg) !important;
  color: var(--fp-blocked-fg) !important;
  border-color: var(--fp-blocked-br) !important;
  cursor: not-allowed;
  text-decoration: line-through;
}

/* Hover / foco */
.flatpickr-calendar.fp-base .flatpickr-day:hover{
  border-color: var(--fp-hover-br);
}
.flatpickr-calendar.fp-base .flatpickr-day:focus-visible{
  outline: 2px solid var(--fp-accent);
  outline-offset: 1px;
}

/* Selección de rango */
.flatpickr-calendar.fp-base .flatpickr-day.inRange{
  background: var(--fp-range-bg);
  border-color: var(--fp-range-br);
  color: #0b1e44;
}
.flatpickr-calendar.fp-base .flatpickr-day.startRange,
.flatpickr-calendar.fp-base .flatpickr-day.endRange,
.flatpickr-calendar.fp-base .flatpickr-day.selected{
  background: var(--fp-accent) !important;
  color: #fff !important;
  border-color: var(--fp-accent-br) !important;
}

/* Badge de precio (estructural, tú le das el look) */
.flatpickr-day .price-tag{
  position: absolute;
  left: 50%;
  bottom: var(--fp-position-price-tag-bottom, 6px);
  transform: translateX(-50%);
  font-size: .64rem;
  line-height: 1;
  padding: 2px 6px;
  border-radius: 999px;
  background: var(--fp-price-bg);
  border: 1px solid var(--fp-price-br);
  color: var(--fp-price-fg);
  pointer-events: none;
  white-space: nowrap;
}
.flatpickr-day.selected .price-tag,
{
  background: rgba(255,255,255,.25);
  border-color: rgba(255,255,255,.45);
  color: var(--fp-range-price-tag-color-hover) !important;
}

.flatpickr-day.startRange .price-tag,
.flatpickr-day.endRange .price-tag
{
  background: rgba(255,255,255,.35);
  border-color: rgba(255,255,255,.55);
  color: var-(--fp-range-price-tag-color-hover-start-end) !important;
}

/* ===== Opcionales (actívalos si te apetecen) ===== */
/* Cuadrícula sutil: descomenta si quieres líneas internas*/
/* .flatpickr-calendar.fp-base .flatpickr-day{
  box-shadow: inset 0 0 0 1px #f1f2f4;
} */

  /* ==== Patch: neutraliza el “preview” azul del rango ==== */
/* Cuando sólo hay una fecha seleccionada y pasas el ratón por los días,
   Flatpickr pinta un azul por defecto. Lo alineamos con nuestro gris. */

.flatpickr-calendar.fp-base .flatpickr-day.inRange,
.flatpickr-calendar.fp-base .flatpickr-day.inRange:hover,
.flatpickr-calendar.fp-base.rangeMode .flatpickr-day:hover {
  background: var(--fp-range-bg) !important;
  border-color: var(--fp-range-br) !important;
  color: var(--fp-range-day-color-hover) !important;
}

.flatpickr-calendar.fp-base .flatpickr-day.inRange .price-tag,
.flatpickr-calendar.fp-base .flatpickr-day.inRange .price-tag:hover,
.flatpickr-calendar.fp-base.rangeMode .flatpickr-day .price-tag:hover {
  background: var(--fp-range-bg-price-tag-hover) !important;
  border-color: var(--fp-range-br-price-tag-hover) !important;
  color: var(--fp-range-price-tag-color-hover2) !important;
}


/* Quitamos cualquier sombra/efecto heredado */
.flatpickr-calendar.fp-base .flatpickr-day.inRange,
.flatpickr-calendar.fp-base .flatpickr-day.selected,
.flatpickr-calendar.fp-base .flatpickr-day.startRange,
.flatpickr-calendar.fp-base .flatpickr-day.endRange {
  box-shadow: none !important;
}

.flatpickr-calendar.fp-base .flatpickr-day.endRange:hover {
  background: var(--fp-accent) !important;
  border-color: var(--fp-accent-br) !important;
  color: #fff !important;
}

.flatpickr-calendar.fp-base .flatpickr-day.endRange .price-tag {
  background: rgba(255,255,255,.35);
  border-color: rgba(255,255,255,.55);
  color: #fff !important;
}




.flatpickr-calendar.fp-base .flatpickr-day.today {
  background: #fffbe6;
  border-color: var(--fp-range-br);
  color: var(--fp-day-fg);
}

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

      const todayYMD = () => {
        const d = new Date();
        const y = d.getFullYear(), m = String(d.getMonth()+1).padStart(2,'0'), day = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${day}`;
        };
        const ymd = d => {
        const y = d.getFullYear(), m = String(d.getMonth()+1).padStart(2,'0'), day = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${day}`;
        };

        const fp = flatpickr(input, {
        locale: 'es',
        mode: 'range',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disable: this.unavailable,     // impide click/hover en no disponibles
        showMonths: 2,
        weekNumbers: false,
        onDayCreate: (dObj, dStr, inst, dayElem) => {
            const k = ymd(dayElem.dateObj);

            // Marcar ocupados de forma consistente (aunque ya estén 'disable')
            if (this.unavailable.includes(k)) {
            dayElem.classList.add('unavailable');
            // Flatpickr ya los hace no seleccionables por 'disable'
            return;
            }

            // Pinta precio
            const price = this.priceMap[k];
            if (price != null) {
            const span = document.createElement('span');
            span.className = 'price-tag';
            const fmt = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });
            span.textContent = fmt.format(price);
            dayElem.setAttribute('title', fmt.format(price));
            dayElem.appendChild(span);
            }
        },
        onReady: (selectedDates, dateStr, inst) => {
            this.fpInstance = inst;
            // aplica tema claro
            inst.calendarContainer.classList.add('fp-base');
            this.fetchMonthPrices(inst);
        },
        onMonthChange: (selectedDates, dateStr, inst) => {
            this.fetchMonthPrices(inst);
        },
        onChange: (selectedDates, dateStr, inst) => {
            this.error = null;
            this.arrival   = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : null;
            this.departure = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : null;
            this.quote = null;

            // Guardas: no permitir rangos que atraviesen bloqueados o pasado
            if (selectedDates.length === 2) {
            const start = selectedDates[0];
            const end   = selectedDates[1];

            // iteramos [start, end) noche a noche
            for (let d = new Date(start); d < end; d.setDate(d.getDate()+1)) {
                const key = ymd(d);
                const isPast = key < todayYMD();
                if (isPast || this.unavailable.includes(key)) {
                inst.clear();
                this.arrival = this.departure = null;
                this.error = 'Ese rango incluye días no disponibles. Elige otro rango.';
                break;
                }
            }
            }
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
