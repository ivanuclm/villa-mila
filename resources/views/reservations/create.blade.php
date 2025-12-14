@php
    $pickerId = uniqid('reservation_picker_');
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reserva – {{ $listing->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f5f6fb; color:#0f172a; margin:0; }
        .shell { max-width: 1200px; margin:0 auto; padding: clamp(1.5rem, 4vw, 3rem); }
        .layout { display:grid; grid-template-columns: minmax(0, 2fr) minmax(260px, 1fr); gap:2rem; align-items:flex-start; }
        @media (max-width: 960px) { .layout { grid-template-columns: 1fr; } }
        .card { background:#fff; border-radius:24px; padding: clamp(1.5rem, 3vw, 2.5rem); box-shadow:0 25px 50px rgba(15,23,42,.1); border:1px solid rgba(148,163,184,.25); }
        .form-section { margin-bottom:2rem; }
        .form-section h2 { margin:0 0 .75rem; font-size:1.15rem; }
        label { display:block; font-size:.9rem; color:#475569; margin-bottom:.25rem; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="date"], select, textarea {
            width:100%; border-radius:14px; border:1px solid rgba(148,163,184,.6); padding:.65rem .75rem; font-size:1rem;
        }
        textarea { min-height:120px; resize:vertical; }
        .grid { display:grid; gap:1rem; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        .summary-card { position:sticky; top:2rem; }
        .summary-card h3 { margin-top:0; }
        .summary-item { display:flex; justify-content:space-between; font-size:.95rem; margin-bottom:.4rem; }
        .error-box { background:#fee2e2; color:#b91c1c; padding:.85rem 1rem; border-radius:14px; margin-bottom:1rem; border:1px solid #fecaca; }
        .button-primary { background:#4f46e5; color:#fff; border:none; border-radius:999px; padding:.85rem 1.6rem; font-size:1rem; font-weight:600; cursor:pointer; width:100%; }
        .button-primary:disabled { opacity:.6; cursor:not-allowed; }
    </style>
</head>
<body x-data="reservationForm({
    listingSlug: @js($listing->slug),
    pickerId: @js($pickerId),
    maxGuests: @js($listing->max_guests),
    arrival: @js($initial['arrival'] ?? null),
    departure: @js($initial['departure'] ?? null),
    guests: @js($initial['guests'] ?? 2),
    quote: @js($quote),
    reservationUrl: @js(route('reservations.create'))
})" x-init="init()">
    <div class="shell">
        <div class="layout">
            <div>
                <div class="card">
                    <form method="POST" action="{{ route('reservations.store') }}" @submit="handleSubmit">
                        @csrf
                        <input type="hidden" name="arrival" :value="arrival || ''">
                        <input type="hidden" name="departure" :value="departure || ''">
                        <input type="hidden" name="guests" :value="guests">
                        <input type="hidden" name="customer_name" :value="fullName">

                        <div class="form-section">
                            <h2>1. Selecciona fechas y viajeros</h2>
                            <label>Rango de fechas</label>
                            <input id="{{ $pickerId }}" placeholder="Llegada — Salida" readonly>
                            <div style="margin-top:1rem" class="grid grid-2">
                                <div>
                                    <label>Viajeros</label>
                                    <input type="number" min="1" :max="maxGuests" x-model.number="guests" @change="refreshPrices()">
                                </div>
                                <div>
                                    <label>&nbsp;</label>
                                    <div style="font-size:.9rem; color:#64748b;">Capacidad máxima {{ $listing->max_guests }} personas.</div>
                                </div>
                            </div>
                            <p class="error-box" x-show="error" x-text="error"></p>
                        </div>

                        <div class="form-section">
                            <h2>2. Tus datos</h2>
                            @if ($errors->any())
                                <div class="error-box">
                                    <strong>Por favor revisa el formulario:</strong>
                                    <ul style="margin:.4rem 0 0 1rem;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="grid grid-2">
                                <div>
                                    <label>Nombre</label>
                                    <input type="text" name="customer_first_name" value="{{ old('customer_first_name') }}" required>
                                </div>
                                <div>
                                    <label>Primer apellido</label>
                                    <input type="text" name="customer_first_surname" value="{{ old('customer_first_surname') }}" required>
                                </div>
                                <div>
                                    <label>Segundo apellido</label>
                                    <input type="text" name="customer_second_surname" value="{{ old('customer_second_surname') }}">
                                </div>
                                <div>
                                    <label>Teléfono</label>
                                    <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="+34 ...">
                                </div>
                                <div>
                                    <label>Email</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required>
                                </div>
                                <div>
                                    <label>Fecha de nacimiento</label>
                                    <input type="date" name="customer_birthdate" value="{{ old('customer_birthdate') }}" required>
                                </div>
                                <div>
                                    <label>País de nacimiento</label>
                                    <input type="text" name="customer_birth_country" value="{{ old('customer_birth_country') }}" required>
                                </div>
                            </div>

                            <div class="grid grid-2" style="margin-top:1rem;">
                                <div>
                                    <label>Tipo de documento</label>
                                    <select name="customer_document_type" required>
                                        @php($docValue = old('customer_document_type'))
                                        <option value="">Selecciona</option>
                                        <option value="dni" {{ $docValue === 'dni' ? 'selected' : '' }}>DNI</option>
                                        <option value="nie" {{ $docValue === 'nie' ? 'selected' : '' }}>NIE</option>
                                        <option value="passport" {{ $docValue === 'passport' ? 'selected' : '' }}>Pasaporte</option>
                                        <option value="other" {{ $docValue === 'other' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Nº de documento</label>
                                    <input type="text" name="customer_document_number" value="{{ old('customer_document_number') }}" required>
                                </div>
                                <div>
                                    <label>Nº de soporte (si aplica)</label>
                                    <input type="text" name="customer_document_support_number" value="{{ old('customer_document_support_number') }}">
                                </div>
                            </div>

                            <div class="form-section" style="margin-top:1.5rem;">
                                <h2>3. Dirección habitual</h2>
                                <div class="grid grid-2">
                                    <div>
                                        <label>Calle y vía</label>
                                        <input type="text" name="customer_address_street" value="{{ old('customer_address_street') }}" required>
                                    </div>
                                    <div>
                                        <label>Número</label>
                                        <input type="text" name="customer_address_number" value="{{ old('customer_address_number') }}">
                                    </div>
                                    <div>
                                        <label>Ciudad</label>
                                        <input type="text" name="customer_address_city" value="{{ old('customer_address_city') }}" required>
                                    </div>
                                    <div>
                                        <label>Provincia / Estado</label>
                                        <input type="text" name="customer_address_province" value="{{ old('customer_address_province') }}" required>
                                    </div>
                                    <div>
                                        <label>País de residencia</label>
                                        <input type="text" name="customer_address_country" value="{{ old('customer_address_country') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h2>4. Comentarios</h2>
                                <label>Información adicional (opcional)</label>
                                <textarea name="notes">{{ old('notes') }}</textarea>
                            </div>

                            <div style="display:flex; align-items:flex-start; gap:.6rem;">
                                <input type="checkbox" id="accept_terms" name="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} required>
                                <label for="accept_terms" style="font-size:.9rem; color:#475569;">He leído y acepto las condiciones de reserva y la política de privacidad.</label>
                            </div>

                            <div style="margin-top:1.5rem;">
                                <button type="submit" class="button-primary" :disabled="!canSubmit">Enviar solicitud</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card summary-card">
                <h3>Resumen</h3>
                <div class="summary-item"><span>Fechas</span><span x-text="summaryDates"></span></div>
                <div class="summary-item"><span>Noches</span><span x-text="quote ? quote.nights : '—'"></span></div>
                <div class="summary-item"><span>Viajeros</span><span x-text="guests"></span></div>
                <hr style="margin:1rem 0; border-color:rgba(148,163,184,.25);">
                <template x-if="quote">
                    <div>
                        <div class="summary-item"><span>Subtotal</span><span x-text="formatCurrency(quote.subtotal)"></span></div>
                        <div class="summary-item"><span>Limpieza</span><span x-text="formatCurrency(quote.cleaning)"></span></div>
                        <div class="summary-item" style="font-weight:600; font-size:1.05rem;"><span>Total</span><span x-text="formatCurrency(quote.total)"></span></div>
                    </div>
                </template>
                <template x-if="!quote">
                    <p style="color:#94a3b8; font-size:.9rem;">Selecciona fechas para ver un presupuesto orientativo.</p>
                </template>
                <p style="margin-top:1rem; font-size:.85rem; color:#475569;">El precio final se confirmará cuando Milagros verifique disponibilidad y estado de tu solicitud.</p>
            </div>
        </div>
    </div>

    <script>
        function reservationForm(config) {
            return {
                listingSlug: config.listingSlug,
                pickerId: config.pickerId,
                maxGuests: config.maxGuests,
                arrival: config.arrival,
                departure: config.departure,
                guests: config.guests || 2,
                quote: config.quote,
                unavailable: [],
                priceMap: {},
                error: null,
                fpInstance: null,
                canSubmit: false,
                get fullName() {
                    const first = document.querySelector('input[name="customer_first_name"]').value?.trim();
                    const last1 = document.querySelector('input[name="customer_first_surname"]').value?.trim();
                    const last2 = document.querySelector('input[name="customer_second_surname"]').value?.trim();
                    return [first, last1, last2].filter(Boolean).join(' ');
                },
                get summaryDates() {
                    if (!this.arrival || !this.departure) return '—';
                    return `${this.arrival} → ${this.departure}`;
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value ?? 0);
                },
                async init() {
                    const res = await fetch(`/api/listings/${this.listingSlug}/unavailable-dates`);
                    const data = res.ok ? await res.json() : { unavailable: [] };
                    this.unavailable = data.unavailable ?? [];

                    const input = document.getElementById(this.pickerId);
                    const fp = flatpickr(input, {
                        locale: 'es',
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        disable: this.unavailable,
                        showMonths: 2,
                        onReady: (selectedDates, dateStr, inst) => {
                            this.fpInstance = inst;
                            inst.calendarContainer.classList.add('fp-base');
                            this.fetchMonthPrices(inst);
                            if (this.arrival && this.departure) {
                                inst.setDate([this.arrival, this.departure], true, 'Y-m-d');
                            }
                            this.canSubmit = !!(this.arrival && this.departure);
                        },
                        onMonthChange: (dates, dateStr, inst) => {
                            this.fetchMonthPrices(inst);
                        },
                        onChange: (selectedDates, dateStr, inst) => {
                            this.error = null;
                            this.arrival = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : null;
                            this.departure = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : null;
                            this.quote = null;
                            this.canSubmit = !!(this.arrival && this.departure);
                            if (this.arrival && this.departure) {
                                this.doQuote();
                            }
                        },
                    });
                    this.fpInstance = fp;
                    if (this.arrival && this.departure) {
                        this.doQuote();
                    }
                },
                async fetchMonthPrices(inst) {
                    const year = inst.currentYear;
                    const month = inst.currentMonth + 1;
                    const months = [`${year}-${String(month).padStart(2, '0')}`];
                    if (inst.config.showMonths > 1) {
                        const next = new Date(year, inst.currentMonth + 1, 1);
                        months.push(`${next.getFullYear()}-${String(next.getMonth() + 1).padStart(2, '0')}`);
                    }
                    for (const ym of months) {
                        const url = `/api/listings/${this.listingSlug}/prices?month=${ym}&guests=${this.guests}`;
                        const r = await fetch(url);
                        if (!r.ok) continue;
                        const { prices } = await r.json();
                        Object.assign(this.priceMap, prices);
                    }
                    inst.redraw();
                },
                async refreshPrices() {
                    if (!this.fpInstance) return;
                    this.priceMap = {};
                    await this.fetchMonthPrices(this.fpInstance);
                    if (this.arrival && this.departure) {
                        this.doQuote();
                    }
                },
                async doQuote() {
                    if (!this.arrival || !this.departure) return;
                    const res = await fetch(`/api/listings/${this.listingSlug}/quote`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ arrival: this.arrival, departure: this.departure, guests: this.guests }),
                    });
                    if (!res.ok) {
                        this.error = 'No se pudo calcular el precio. Inténtalo de nuevo.';
                        this.quote = null;
                        return;
                    }
                    this.quote = await res.json();
                },
                handleSubmit(event) {
                    if (!this.arrival || !this.departure) {
                        event.preventDefault();
                        this.error = 'Selecciona un rango de fechas válido.';
                        return false;
                    }
                    if (this.guests < 1) {
                        event.preventDefault();
                        this.error = 'Indica el número de personas.';
                        return false;
                    }
                    this.error = null;
                    return true;
                },
            }
        }
    </script>
</body>
</html>
