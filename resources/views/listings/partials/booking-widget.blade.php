@php
    $widgetId = uniqid('booking_widget_');
    $pickerId = $widgetId . '_daterange';
@endphp

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --app-font: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu;
            --card-bg: #111;
            --card-fg: #eee;
            --card-radius: 16px;
            --fp-bg: #f5f5f5ff;
            --fp-border: #e6e6ea;
            --fp-shadow: 0 10px 30px rgba(0,0,0,.08);
            --fp-radius: 14px;
            --fp-header-bg: #f7f7f9;
            --fp-header-fg: #0f172a;
            --fp-header-weight: 700;
            --fp-weekday-fg: #6b7280;
            --fp-weekday-weight: 600;
            --fp-day-bg: #ffffff;
            --fp-day-fg: #111827;
            --fp-day-border: #eef0f3;
            --fp-day-radius: 8px;
            --fp-day-height: 48px;
            --fp-day-width: 14.2857%;
            --fp-out-bg: #fafafa;
            --fp-out-fg: #a1a1aa;
            --fp-disabled-bg: #f2f3f6;
            --fp-disabled-fg: #9ca3af;
            --fp-disabled-br: #eceef2;
            --fp-blocked-bg: #f1f2f5;
            --fp-blocked-fg: #9aa0a6;
            --fp-blocked-br: #e8eaf0;
            --fp-hover-br: #d9dee6;
            --fp-focus-outline: #3b82f6;
            --fp-selected-bg: #3b82f6;
            --fp-selected-br: #2d5dd3;
            --fp-selected-fg: #ffffff;
            --fp-accent: #3b82f6;
            --fp-accent-br: #2d5dd3;
            --fp-accent-fg: #ffffff;
            --fp-range-bg: #eaf2ff;
            --fp-range-br: #d6e4ff;
            --fp-range-day-fg: #0b1e44;
            --fp-range-conflict-fg: #ef4444;
            --fp-today-bg: #fffbe6;
            --fp-today-br: #ffec3d;
            --fp-today-fg: #613400;
            --fp-price-font: .64rem;
            --fp-price-pad-v: 2px;
            --fp-price-pad-h: 6px;
            --fp-price-radius: 999px;
            --fp-price-bg: rgba(17,24,39,.06);
            --fp-price-br: #e5e7eb;
            --fp-price-fg: #111;
            --fp-price-bottom: 6px;
            --fp-price-inrange-bg: #d6e4ff;
            --fp-price-inrange-br: #a9c1ff;
            --fp-price-inrange-fg: #2d5dd3;
            --fp-price-selected-bg: rgba(255,255,255,.35);
            --fp-price-selected-br: rgba(255,255,255,.55);
            --fp-price-selected-fg: #ffffff;
            --fp-range-price-conflict-fg: #b91c1c;
            --fp-range-price-conflict-bg: #fee2e2;
            --fp-range-price-conflict-br: #fca5a5;
        }

        .legacy-widget-card {
            font-family: var(--app-font);
            max-width: 900px;
            margin: 0 auto;
            background: var(--card-bg);
            color: var(--card-fg);
            border-radius: var(--card-radius);
            padding: 1.5rem;
        }

        .legacy-widget-row {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 820px) {
            .legacy-widget-row { grid-template-columns: 1fr; }
        }

        .legacy-widget-row > div {
            background: #1b1b1b;
            border-radius: 12px;
            padding: 1rem;
        }

        .legacy-widget label {
            display:block;
            font-size: .9rem;
            opacity:.8;
            margin-bottom:.25rem;
        }

        .legacy-widget input,
        .legacy-widget select {
            width: 100%;
            padding: .6rem .7rem;
            border-radius: 10px;
            border: 1px solid #333;
            background: #0f0f0f;
            color: #fff;
        }

        .legacy-widget textarea {
            width: 100%;
            padding: .6rem .7rem;
            border-radius:10px;
            border:1px solid #333;
            background:#0f0f0f;
            color:#fff;
        }

        .legacy-widget button {
            width: 100%;
            padding: .7rem 1rem;
            border-radius: 10px;
            background: #6366f1;
            border: 0;
            color: #fff;
            cursor: pointer;
        }

        .legacy-widget .muted { opacity:.7; font-size:.9rem; }

        .flatpickr-calendar.fp-base { background: var(--fp-bg); border: 1px solid var(--fp-border); box-shadow: var(--fp-shadow); border-radius: var(--fp-radius); overflow: hidden; color: var(--fp-day-fg); }
        .flatpickr-calendar.fp-base .flatpickr-months { background: var(--fp-header-bg); border-bottom: 1px solid var(--fp-border); }
        .flatpickr-calendar.fp-base .flatpickr-current-month { color: var(--fp-header-fg); font-weight: var(--fp-header-weight); }
        .flatpickr-calendar.fp-base .flatpickr-weekdays { background: var(--fp-header-bg); border-bottom: 1px solid var(--fp-border); }
        .flatpickr-calendar.fp-base span.flatpickr-weekday { color: var(--fp-weekday-fg); font-weight: var(--fp-weekday-weight); letter-spacing: .02em; }
        .flatpickr-calendar.fp-base .flatpickr-days,
        .flatpickr-calendar.fp-base .dayContainer { background: var(--fp-bg); padding: 0; border: 0; }
        .flatpickr-calendar.fp-base .flatpickr-day { position: relative; width: var(--fp-day-width) !important; height: var(--fp-day-height); margin: 0; border-radius: var(--fp-day-radius); border: 1px solid var(--fp-day-border); background: var(--fp-day-bg); color: var(--fp-day-fg); line-height: 1.1; box-sizing: border-box; }
        .flatpickr-calendar.fp-base .flatpickr-day.prevMonthDay,
        .flatpickr-calendar.fp-base .flatpickr-day.nextMonthDay { background: var(--fp-out-bg); color: var(--fp-out-fg); border-color: var(--fp-day-border); }
        .flatpickr-calendar.fp-base .flatpickr-day.flatpickr-disabled,
        .flatpickr-calendar.fp-base .flatpickr-day.flatpickr-disabled:hover { background: var(--fp-disabled-bg) !important; color: var(--fp-disabled-fg) !important; border-color: var(--fp-disabled-br) !important; cursor: not-allowed; text-decoration: none; opacity: 1; }
        .flatpickr-calendar.fp-base .flatpickr-day.unavailable,
        .flatpickr-calendar.fp-base .flatpickr-day.unavailable:hover { background: var(--fp-blocked-bg) !important; color: var(--fp-blocked-fg) !important; border-color: var(--fp-blocked-br) !important; cursor: not-allowed; text-decoration: line-through; }
        .flatpickr-calendar.fp-base .flatpickr-day:hover { border-color: var(--fp-hover-br); }
        .flatpickr-calendar.fp-base .flatpickr-day:focus-visible { outline: 2px solid var(--fp-focus-outline); outline-offset: 1px; }
        .flatpickr-calendar.fp-base .flatpickr-day.inRange { background: var(--fp-range-bg); border-color: var(--fp-range-br); color: var(--fp-range-day-fg); }
        .flatpickr-calendar.fp-base .flatpickr-day.startRange,
        .flatpickr-calendar.fp-base .flatpickr-day.endRange,
        .flatpickr-calendar.fp-base .flatpickr-day.selected { background: var(--fp-selected-bg) !important; border-color: var(--fp-selected-br) !important; color: var(--fp-selected-fg) !important; box-shadow: none !important; }
        .flatpickr-calendar.fp-base .flatpickr-day.today { background: var(--fp-today-bg) !important; border-color: var(--fp-today-br) !important; color: var(--fp-today-fg) !important; }
        .flatpickr-day .price-tag { position: absolute; left: 50%; bottom: var(--fp-price-bottom); transform: translateX(-50%); font-size: var(--fp-price-font); line-height: 1; padding: var(--fp-price-pad-v) var(--fp-price-pad-h); border-radius: var(--fp-price-radius); background: var(--fp-price-bg); border: 1px solid var(--fp-price-br); color: var(--fp-price-fg); pointer-events: none; white-space: nowrap; }
        .flatpickr-calendar.fp-base .flatpickr-day.inRange .price-tag,
        .flatpickr-calendar.fp-base.rangeMode .flatpickr-day:hover .price-tag { background: var(--fp-price-inrange-bg); border-color: var(--fp-price-inrange-br); color: var(--fp-price-inrange-fg); }
        .flatpickr-calendar.fp-base .flatpickr-day.startRange .price-tag,
        .flatpickr-calendar.fp-base .flatpickr-day.endRange .price-tag,
        .flatpickr-calendar.fp-base .flatpickr-day.selected .price-tag { background: var(--fp-price-selected-bg); border-color: var(--fp-price-selected-br); color: var(--fp-price-selected-fg); }
        .flatpickr-calendar.fp-base .flatpickr-day.endRange:hover .price-tag { background: var(--fp-price-selected-bg); border-color: var(--fp-price-selected-br); color: var(--fp-price-selected-fg); }
        .flatpickr-calendar.fp-base .flatpickr-day.inRange,
        .flatpickr-calendar.fp-base .flatpickr-day.inRange:hover,
        .flatpickr-calendar.fp-base.rangeMode .flatpickr-day:hover { background: var(--fp-range-bg); border-color: var(--fp-range-br); color: var(--fp-range-day-fg); }
        .flatpickr-calendar.fp-base .flatpickr-day.endRange,
        .flatpickr-calendar.fp-base .flatpickr-day.endRange:hover { background: var(--fp-accent); border-color: var(--fp-accent-br); color: var(--fp-accent-fg); }
        .flatpickr-calendar.fp-base.fp-no-cross .flatpickr-day:hover { background: var(--fp-day-bg) !important; border-color: var(--fp-day-border) !important; cursor: not-allowed !important; }
        .flatpickr-day.fp-hover-blocked { position: relative; color: var(--fp-range-conflict-fg) !important; }
        .flatpickr-day:hover.fp-hover-blocked .price-tag { color: var(--fp-range-price-conflict-fg) !important; background: var(--fp-range-price-conflict-bg) !important; border-color: var(--fp-range-price-conflict-br) !important; }
        .flatpickr-day.fp-hover-blocked::after { content: ""; position: absolute; inset: 0; border: 2px dashed var(--fp-range-conflict-fg); border-radius: var(--fp-day-radius); pointer-events: none; }
    </style>
@endonce

<div class="legacy-widget legacy-widget-card"
     x-data="bookingWidget{{ $widgetId }}()"
     x-init="init()">
    <h2 style="margin:0 0 .25rem 0">{{ $listing['name'] }}</h2>
    <p class="muted">{{ $listing['address'] ?? '' }} — Licencia: {{ $listing['license_number'] ?? '—' }}</p>

    <div class="legacy-widget-row" style="margin-top:1rem">
        <div>
            <h3 style="margin-top:0">Selecciona fechas</h3>
            <label>Rango de fechas</label>
            <input id="{{ $pickerId }}" placeholder="Llegada — Salida" readonly>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:.75rem; margin-top:.75rem">
                <div>
                    <label>Viajeros</label>
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
                    <p><strong>Precio por noche:</strong> €<span x-text="quote.price_per_night.toFixed(2)"></span></p>
                    <p><strong>Subtotal:</strong> €<span x-text="quote.subtotal.toFixed(2)"></span></p>
                    <p><strong>Limpieza:</strong> €<span x-text="quote.cleaning.toFixed(2)"></span></p>
                    <hr>
                    <p><strong>Total:</strong> €<span x-text="quote.total.toFixed(2)"></span></p>
                </div>
            </template>
            <template x-if="!quote">
                <p class="muted">Selecciona fechas y pulsa “Calcular precio”.</p>
            </template>

            <hr style="margin:1rem 0; border-color:#27272a;">

            <h4 style="margin:0 0 .5rem 0; font-size:.95rem;">Tus datos</h4>

            <div style="display:flex; flex-direction:column; gap:.5rem;">
                <div>
                    <label>Nombre y apellidos</label>
                    <input type="text" x-model="customer_name" placeholder="Nombre completo">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" x-model="customer_email" placeholder="tucorreo@ejemplo.com">
                </div>
                <div>
                    <label>Teléfono (opcional)</label>
                    <input type="tel" x-model="customer_phone" placeholder="+34 ...">
                </div>
                <div>
                    <label>Comentarios (opcional)</label>
                    <textarea x-model="notes" rows="3" style="resize:vertical;"></textarea>
                </div>
                <div style="display:flex; align-items:flex-start; gap:.5rem; margin-top:.25rem;">
                    <input id="accept_terms_{{ $widgetId }}" type="checkbox" x-model="accept_terms" style="width:auto; margin-top:.15rem;">
                    <label for="accept_terms_{{ $widgetId }}" style="margin:0; font-size:.8rem; opacity:.8;">
                        He leído y acepto las condiciones de reserva y la política de privacidad.
                    </label>
                </div>

                <button
                    @click="submitBooking()"
                    :disabled="!quote || bookingLoading"
                    x-text="bookingLoading ? 'Enviando...' : 'Solicitar reserva'">
                </button>

                <p class="muted" x-show="bookingError" x-text="bookingError" style="color:#fca5a5;"></p>
                <p class="muted" x-show="bookingSuccess" x-text="bookingSuccess" style="color:#bbf7d0;"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function bookingWidget{{ $widgetId }}() {
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
            customer_name: '',
            customer_email: '',
            customer_phone: '',
            notes: '',
            accept_terms: false,
            bookingSuccess: null,
            bookingError: null,
            bookingLoading: false,
            pickerId: @js($pickerId),
            csrfToken: @js(csrf_token()),
            ymd(d) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate() + 0).padStart(2, '0');
                return `${y}-${m}-${day}`;
            },
            rangeCrossesUnavailable(startDate, endDate) {
                const locked = new Set(this.unavailable);
                const a = startDate < endDate ? startDate : endDate;
                const b = startDate < endDate ? endDate : startDate;
                for (let d = new Date(a); d < b; d.setDate(d.getDate() + 1)) {
                    if (locked.has(this.ymd(d))) return true;
                }
                return false;
            },
            async init() {
                const res = await fetch(`/api/listings/${this.listingSlug}/unavailable-dates`);
                const data = res.ok ? await res.json() : { unavailable: [] };
                this.unavailable = data.unavailable ?? [];

                const input = document.getElementById(this.pickerId);
                const todayYMD = () => this.ymd(new Date());

                const fp = flatpickr(input, {
                    locale: 'es',
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    disable: this.unavailable,
                    showMonths: 2,
                    weekNumbers: false,
                    onDayCreate: (dObj, dStr, inst, dayElem) => {
                        const key = this.ymd(dayElem.dateObj);
                        if (this.unavailable.includes(key)) {
                            dayElem.classList.add('unavailable');
                            return;
                        }
                        const price = this.priceMap[key];
                        if (price != null) {
                            const span = document.createElement('span');
                            span.className = 'price-tag';
                            const fmt = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });
                            span.textContent = fmt.format(price);
                            dayElem.setAttribute('title', fmt.format(price));
                            dayElem.appendChild(span);
                        }
                        const enter = () => {
                            if (!this.fpInstance || this.fpInstance.selectedDates.length !== 1) return;
                            const start = this.fpInstance.selectedDates[0];
                            const hover = dayElem.dateObj;
                            if (hover > start && this.rangeCrossesUnavailable(start, hover)) {
                                this.fpInstance.calendarContainer.classList.add('fp-no-cross');
                                dayElem.classList.add('fp-hover-blocked');
                            }
                        };
                        const leave = () => {
                            if (!this.fpInstance) return;
                            this.fpInstance.calendarContainer.classList.remove('fp-no-cross');
                            dayElem.classList.remove('fp-hover-blocked');
                        };
                        const stopIfBlocked = (e) => {
                            if (!this.fpInstance || this.fpInstance.selectedDates.length !== 1) return;
                            const start = this.fpInstance.selectedDates[0];
                            const hover = dayElem.dateObj;
                            if (hover > start && this.rangeCrossesUnavailable(start, hover)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        };
                        dayElem.addEventListener('mouseenter', enter);
                        dayElem.addEventListener('mouseleave', leave);
                        dayElem.addEventListener('click', stopIfBlocked);
                    },
                    onReady: (selectedDates, dateStr, inst) => {
                        this.fpInstance = inst;
                        inst.calendarContainer.classList.add('fp-base');
                        this.fetchMonthPrices(inst);
                    },
                    onMonthChange: (selectedDates, dateStr, inst) => {
                        this.fetchMonthPrices(inst);
                    },
                    onChange: (selectedDates, dateStr, inst) => {
                        this.error = null;
                        this.arrival = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : null;
                        this.departure = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : null;
                        this.quote = null;

                        if (selectedDates.length === 2) {
                            const start = selectedDates[0];
                            const end = selectedDates[1];
                            for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
                                const key = this.ymd(d);
                                if (key < todayYMD() || this.unavailable.includes(key)) {
                                    inst.clear();
                                    this.arrival = this.departure = null;
                                    this.error = 'Ese rango incluye días no disponibles. Elige otro rango.';
                                    break;
                                }
                            }
                        }

                        inst.calendarContainer.classList.remove('fp-no-cross');
                    },
                });
            },
            async fetchMonthPrices(inst) {
                const year = inst.currentYear;
                const month = inst.currentMonth + 1;
                const months = [`${year}-${String(month).padStart(2,'0')}`];
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
                inst.redraw();
            },
            async refreshPrices() {
                if (!this.fpInstance) return;
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
                        'X-CSRF-TOKEN': this.csrfToken,
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
            async submitBooking() {
                this.bookingError = null;
                this.bookingSuccess = null;

                if (!this.arrival || !this.departure) {
                    this.bookingError = 'Selecciona fechas antes de reservar.';
                    return;
                }

                if (!this.quote) {
                    this.bookingError = 'Calcula primero el precio antes de reservar.';
                    return;
                }

                if (!this.accept_terms) {
                    this.bookingError = 'Debes aceptar las condiciones de la reserva.';
                    return;
                }

                this.bookingLoading = true;

                try {
                    const res = await fetch(`/api/listings/${this.listingSlug}/book`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            arrival: this.arrival,
                            departure: this.departure,
                            guests: this.guests,
                            customer_name: this.customer_name,
                            customer_email: this.customer_email,
                            customer_phone: this.customer_phone || null,
                            notes: this.notes || null,
                            accept_terms: this.accept_terms ? 1 : 0,
                        }),
                    });

                    const data = await res.json();

                    if (!res.ok || !data.ok) {
                        this.bookingError = data.error ?? 'No se pudo completar la reserva.';
                        return;
                    }

                    this.bookingSuccess = `Reserva creada correctamente. Estado: ${data.booking.status.toUpperCase()}.`;
                } catch (e) {
                    console.error(e);
                    this.bookingError = 'Ha ocurrido un error inesperado. Inténtalo de nuevo en unos minutos.';
                } finally {
                    this.bookingLoading = false;
                }
            },
        };
    }
</script>
