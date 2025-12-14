<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mi reserva – {{ $booking->listing->name ?? 'Villa Mila' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
            background: #f5f6fb;
            color: #0f172a;
        }
        .portal-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(1.5rem, 4vw, 3rem);
        }
        .portal-header {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.15);
        }
        .portal-header__bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: brightness(0.8);
        }
        .portal-header__bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(15,23,42,0.75), rgba(15,23,42,0.25));
        }
        .portal-header__body {
            position: relative;
            padding: 2rem;
            color: #f8fafc;
            background: linear-gradient(120deg, rgba(15,23,42,0.75), rgba(15,23,42,0.45));
        }
        .portal-header h1 {
            margin: 0 0 0.5rem;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
        }
        .portal-header .info-list li {
            color: rgba(248, 250, 252, 0.9);
        }
        .portal-header .info-list strong {
            color: #fff;
        }
        .portal-header .badge {
            background: rgba(15, 23, 42, 0.6);
            color: #e0e7ff;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .portal-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(148, 163, 184, 0.3);
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
        }
        .portal-card--muted {
            background: #f1f5f9;
            border-style: dashed;
            border-color: rgba(148,163,184,.5);
        }
        .info-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0 0;
            display: grid;
            gap: 0.35rem;
        }
        .info-list li {
            font-size: 0.95rem;
            color: #475569;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.2rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: #eef2ff;
            color: #4338ca;
        }
        form .field {
            margin-bottom: 0.9rem;
        }
        form label {
            display: block;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.2rem;
        }
        form input,
        form select {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            padding: 0.65rem;
            font-size: 0.95rem;
        }
        form button {
            background: linear-gradient(120deg, #4f46e5, #7c3aed);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.85rem 1.4rem;
            font-size: 0.95rem;
            cursor: pointer;
            width: 100%;
        }
        .signature-pad {
            border: 1px dashed rgba(148, 163, 184, 0.7);
            border-radius: 14px;
            background: #f8fafc;
            position: relative;
        }
        .signature-pad canvas {
            width: 100%;
            height: 180px;
            border-radius: 14px;
        }
        .signature-pad__actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 0.35rem;
        }
        .signature-pad__actions button {
            width: auto;
            padding: 0.4rem 0.9rem;
            background: #e2e8f0;
            color: #475569;
            border-radius: 10px;
            font-size: 0.85rem;
        }
        .guest-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .guest-card {
            border: 1px solid rgba(148,163,184,0.35);
            border-radius: 18px;
            padding: 1rem;
            background: #f8fafc;
        }
        .guest-card header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .guest-card img {
            max-width: 160px;
            border-radius: 12px;
            border: 1px solid rgba(148,163,184,.4);
            background:#fff;
        }
        .guest-card details {
            margin-top: 0.75rem;
        }
        .guest-card details summary {
            cursor: pointer;
            font-weight: 600;
            color: #4338ca;
        }
        .guest-card form {
            margin-top: 0.75rem;
        }
        .guest-card .actions {
            margin-top: 0.5rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .link-button {
            background: transparent;
            border: none;
            color: #dc2626;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
        }
        .status-message {
            background: #ecfccb;
            color: #365314;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .status-message--error {
            background: #fee2e2;
            color: #b91c1c;
        }
        .portal-grid--status {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
        .status-checklist {
            list-style: none;
            padding: 0;
            margin: 1rem 0 0;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }
        .status-checklist li {
            display: flex;
            gap: 0.45rem;
            font-size: 0.9rem;
            color: #475569;
        }
        .status-checklist li::before {
            content: "•";
            color: #4f46e5;
            font-weight: 700;
        }
        .info-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            background: #eef2ff;
            color: #4338ca;
            margin-right: 0.45rem;
            margin-bottom: 0.35rem;
        }
        .payment-box {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid rgba(148,163,184,.4);
        }
        .payment-box__row {
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
            font-size: 0.9rem;
            margin-bottom: 0.35rem;
            color: #0f172a;
        }
        .payment-box__label {
            display: block;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .copy-row {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        .copy-row input {
            flex: 1;
            border-radius: 12px;
            border: 1px solid rgba(148,163,184,.6);
            padding: 0.55rem;
            font-size: 0.9rem;
        }
        .copy-row button {
            border-radius: 10px;
            border: none;
            background: #4f46e5;
            color: #fff;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            cursor: pointer;
        }
        .fieldset-disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        @media (max-width: 640px) {
            .portal-header {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @php
        use Illuminate\Support\Facades\Storage;
        use App\Support\BookingFlow;

        $flow = BookingFlow::context($booking);
        $coverUrl = $booking->listing?->cover_url;
        $portalUrl = $booking->public_access_token ? route('guest.portal.show', $booking->public_access_token) : null;
        $guestLimitReached = $booking->guestEntries->count() >= $booking->guests;
    @endphp
    <div class="portal-shell">
        <div class="portal-header">
            @if ($coverUrl)
                <div class="portal-header__bg" style="background-image:url('{{ $coverUrl }}');"></div>
            @endif
            <div class="portal-header__body">
                <h1>Hola, {{ $booking->customer_name }}</h1>
                <p style="color:#e2e8f0;">
                    Estado actual: <span class="badge">{{ $booking->status->label() }}</span>
                </p>
                <ul class="info-list">
                    <li><strong>Estancia:</strong> {{ $booking->arrival->format('d M Y') }} → {{ $booking->departure->format('d M Y') }} ({{ $booking->guests }} huéspedes)</li>
                    <li><strong>Total estimado:</strong> €{{ number_format($booking->total, 2, ',', '.') }}</li>
                    <li><strong>Contacto:</strong> {{ $flow['ownerName'] }} {{ $flow['ownerPhone'] ? '· ' . $flow['ownerPhone'] : '' }}</li>
                </ul>
            </div>
        </div>

        @if (session('portal_status'))
            <div class="status-message">
                {{ session('portal_status') }}
            </div>
        @endif

        @if (session('portal_error'))
            <div class="status-message status-message--error">
                {{ session('portal_error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="status-message status-message--error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="portal-grid portal-grid--status" style="margin-bottom: 1.5rem;">
            <div class="portal-card">
                <span class="badge">Guía del proceso</span>
                <h2 style="margin:0.4rem 0 0.25rem 0;">{{ $flow['guide']['title'] }}</h2>
                <p style="color:#475569; margin-top:0;">{{ $flow['guide']['description'] }}</p>
                <ul class="status-checklist">
                    @foreach ($flow['guide']['items'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="portal-card">
                <h2 style="margin-top:0;">Contacto directo</h2>
                <ul class="info-list">
                    @if ($flow['ownerPhone'])
                        <li><strong>Teléfono:</strong> {{ $flow['ownerPhone'] }}</li>
                    @endif
                    @if ($flow['ownerEmail'])
                        <li><strong>Email:</strong> {{ $flow['ownerEmail'] }}</li>
                    @endif
                </ul>

                @if ($flow['showPaymentInstructions'] && ! empty($flow['payment']['bank_account']))
                    <div class="payment-box">
                        <p style="margin:0 0 .75rem 0; font-weight:600;">Transferencia bancaria</p>
                        <div class="payment-box__row">
                            <div>
                                <span class="payment-box__label">Titular</span>
                                {{ $flow['payment']['account_name'] ?? $flow['ownerName'] }}
                            </div>
                            <div>
                                <span class="payment-box__label">IBAN</span>
                                <code>{{ $flow['payment']['bank_account'] }}</code>
                            </div>
                        </div>
                        @if (! empty($flow['payment']['instructions']))
                            <p style="margin:0; font-size:.85rem; color:#64748b;">
                                {{ $flow['payment']['instructions'] }}
                            </p>
                        @endif
                    </div>
                @endif

                @if ($flow['showHouseInfo'])
                    <div style="margin-top:1rem;">
                        <span class="info-chip">Check-in {{ config('villa.check_in_time') }}</span>
                        <span class="info-chip">Check-out {{ config('villa.check_out_time') }}</span>
                    </div>
                @endif
            </div>

            <div class="portal-card">
                <h2 style="margin-top:0;">Enlaces útiles</h2>
                <p style="margin:0; color:#475569;">
                    Guarda este enlace personal para volver cuando quieras. Puedes compartirlo con tus acompañantes
                    para que completen sus datos.
                </p>
                @if ($portalUrl)
                    <div class="copy-row">
                        <input type="text" value="{{ $portalUrl }}" readonly id="portalLinkField">
                        <button type="button" id="copyPortalLink">Copiar</button>
                    </div>
                @endif

                @if ($flow['showHouseInfo'])
                    <ul class="info-list" style="margin-top:1rem;">
                        <li>
                            <strong>Normas de la casa:</strong>
                            @if ($flow['houseRulesUrl'])
                                <a href="{{ $flow['houseRulesUrl'] }}" target="_blank" rel="noopener">Descargar PDF</a>
                            @else
                                Estarán disponibles próximamente.
                            @endif
                        </li>
                        <li>
                            <strong>Guía rápida / cómo llegar:</strong>
                            @if ($flow['guidebookUrl'])
                                <a href="{{ $flow['guidebookUrl'] }}" target="_blank" rel="noopener">Abrir guía</a>
                            @else
                                La enviaremos por correo antes del check-in.
                            @endif
                        </li>
                        <li>
                            <strong>Contrato digital:</strong>
                            @if ($booking->contract_document_url)
                                <a href="{{ $booking->contract_document_url }}" target="_blank" rel="noopener">Descargar contrato firmado</a>
                            @elseif ($flow['contractUrl'])
                                <a href="{{ $flow['contractUrl'] }}" target="_blank" rel="noopener">Ver borrador</a>
                            @else
                                Lo firmaremos junto con el registro de viajeros.
                            @endif
                        </li>
                    </ul>
                @endif
            </div>
        </div>

        @if (! $flow['canSeeTravelerSection'])
            <div class="portal-card portal-card--muted">
                <h2 style="margin:0;">Registro de viajeros</h2>
                <p style="color:#475569;">
                    Activaremos este formulario cuando la reserva esté confirmada. Mientras tanto, puedes compartir
                    esta página con tus acompañantes para que tengan la información básica.
                </p>
            </div>
        @else
        <div class="portal-grid">
            <div class="portal-card">
                <h2 style="margin-top:0;">Viajeros registrados ({{ $booking->guestEntries->count() }}/{{ $booking->guests }})</h2>
                @if (! $flow['canManageGuests'] && $booking->guestEntries->count() > 0)
                    <p style="color:#64748b; font-size:.9rem;">
                        El registro está bloqueado porque la reserva está en estado {{ $booking->status->label() }}.
                    </p>
                @endif
                <div class="guest-list">
                    @forelse ($booking->guestEntries as $guest)
                        <div class="guest-card">
                            <header>
                                <div>
                                    <p style="margin:0;font-weight:600;">{{ $guest->full_name }}</p>
                                    <p style="margin:0;color:#475569;font-size:.9rem;">
                                        Documento: {{ $guest->document_number ?: '—' }} · Nacionalidad: {{ $guest->nationality ?: '—' }}
                                    </p>
                                    <p style="margin:0;color:#94a3b8;font-size:.85rem;">Añadido el {{ $guest->created_at->format('d/m/Y') }}</p>
                                </div>
                                @if ($guest->signature_path)
                                    <img src="{{ Storage::disk('public')->url($guest->signature_path) }}" alt="Firma de {{ $guest->full_name }}">
                                @endif
                            </header>

                            @if ($flow['canManageGuests'])
                                <details>
                                    <summary>Editar datos</summary>
                                    <form method="POST" action="{{ route('guest.portal.guests.update', [$booking->public_access_token, $guest]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="field">
                                            <label>Nombre completo</label>
                                            <input type="text" name="full_name" value="{{ $guest->full_name }}" required>
                                        </div>
                                        <div class="field">
                                            <label>Número de documento</label>
                                            <input type="text" name="document_number" value="{{ $guest->document_number }}">
                                        </div>
                                        <div class="field">
                                            <label>Nacionalidad</label>
                                            <input type="text" name="nationality" value="{{ $guest->nationality }}">
                                        </div>
                                        <div class="field">
                                            <label>Fecha de nacimiento</label>
                                            <input type="date" name="birthdate" value="{{ $guest->birthdate?->format('Y-m-d') }}">
                                        </div>
                                        <div class="field">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $guest->email }}">
                                        </div>
                                        <div class="field">
                                            <label>Teléfono</label>
                                            <input type="text" name="phone" value="{{ $guest->phone }}">
                                        </div>
                                        <button type="submit">Actualizar</button>
                                    </form>
                                </details>

                                <div class="actions">
                                    <form method="POST" action="{{ route('guest.portal.guests.destroy', [$booking->public_access_token, $guest]) }}" onsubmit="return confirm('¿Eliminar este viajero?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="link-button">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p style="color:#94a3b8;">Aún no has añadido los datos de los acompañantes.</p>
                    @endforelse
                </div>
            </div>

            @if ($flow['canAddGuests'])
            <div class="portal-card">
                <h2 style="margin-top:0;">Añadir viajero</h2>
                <p style="color:#94a3b8;font-size:.9rem;margin-bottom:1rem;">Necesitamos estos datos para el registro oficial de viajeros. Repite el formulario por cada acompañante.</p>

                <form method="POST" action="{{ route('guest.portal.guests.store', $booking->public_access_token) }}" id="guestForm">
                    @csrf
                    <fieldset style="border:none;padding:0;margin:0;" @if($guestLimitReached) disabled class="fieldset-disabled" @endif>
                        <div class="field">
                            <label>Nombre completo</label>
                            <input type="text" name="full_name" required>
                        </div>
                        <div class="field">
                            <label>Número de documento</label>
                            <input type="text" name="document_number">
                        </div>
                        <div class="field">
                            <label>Nacionalidad</label>
                            <input type="text" name="nationality">
                        </div>
                        <div class="field">
                            <label>Fecha de nacimiento</label>
                            <input type="date" name="birthdate">
                        </div>
                        <div class="field">
                            <label>Email (opcional)</label>
                            <input type="email" name="email">
                        </div>
                        <div class="field">
                            <label>Teléfono (opcional)</label>
                            <input type="text" name="phone">
                        </div>
                        <div class="field">
                            <label>Firma digital</label>
                            <div class="signature-pad">
                                <canvas id="signatureCanvas"></canvas>
                            </div>
                            <div class="signature-pad__actions">
                                <button type="button" id="clearSignature">Limpiar</button>
                            </div>
                            <input type="hidden" name="signature_data" id="signatureData">
                        </div>
                        <button type="submit">Guardar viajero</button>
                    </fieldset>
                </form>
                @if($guestLimitReached)
                    <p style="color:#dc2626;font-size:.85rem;margin-top:.75rem;">
                        Ya has registrado el número máximo de viajeros indicados en la reserva.
                    </p>
                @endif
            </div>
            @elseif ($flow['canManageGuests'])
                <div class="portal-card portal-card--muted">
                    <h2 style="margin:0;">Registro completo</h2>
                    <p style="color:#475569;">Has alcanzado el número máximo de viajeros. Para modificar algo, edita o elimina una ficha existente.</p>
                </div>
            @endif
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signatureCanvas');
        let signaturePad = null;

        if (canvas) {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: '#f8fafc',
                penColor: '#0f172a',
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            const clearButton = document.getElementById('clearSignature');
            if (clearButton) {
                clearButton.addEventListener('click', () => signaturePad.clear());
            }

            const guestForm = document.getElementById('guestForm');
            if (guestForm) {
                guestForm.addEventListener('submit', () => {
                    if (!signaturePad.isEmpty()) {
                        document.getElementById('signatureData').value = signaturePad.toDataURL('image/png');
                    }
                });
            }
        }

        const copyBtn = document.getElementById('copyPortalLink');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => {
                const input = document.getElementById('portalLinkField');
                if (!input) return;

                input.select();
                input.setSelectionRange(0, input.value.length);

                const finish = () => {
                    copyBtn.textContent = 'Copiado';
                    setTimeout(() => copyBtn.textContent = 'Copiar', 2400);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(input.value).then(finish).catch(() => {
                        document.execCommand('copy');
                        finish();
                    });
                } else {
                    document.execCommand('copy');
                    finish();
                }
            });
        }
    </script>
</body>
</html>
