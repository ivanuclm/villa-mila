<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Solicitud enviada – {{ $booking->listing->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f5f6fb; color:#0f172a; margin:0; }
        .shell { max-width: 960px; margin: 0 auto; padding: clamp(1.5rem, 4vw, 3rem); }
        .card { background:#fff; border-radius:24px; padding: clamp(1.5rem,3vw,2.5rem); box-shadow:0 30px 60px rgba(15,23,42,.08); border:1px solid rgba(148,163,184,.2); }
        h1 { font-size: clamp(1.8rem, 3vw, 2.4rem); margin-bottom:.5rem; }
        .muted { color:#64748b; font-size:.95rem; }
        .summary { margin:1.5rem 0; display:grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap:1rem; }
        .summary div { background:#f1f5f9; border-radius:16px; padding:1.1rem; border:1px dashed rgba(148,163,184,.6); }
        a.button { display:inline-flex; align-items:center; justify-content:center; gap:.35rem; border-radius:999px; padding:.85rem 1.4rem; background:#4f46e5; color:#fff; font-weight:600; text-decoration:none; }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card">
            <h1>¡Gracias, {{ $booking->customer_first_name ?? $booking->customer_name }}!</h1>
            <p class="muted">Hemos recibido tu solicitud de reserva para {{ $booking->listing->name }}. Te hemos enviado un correo con los próximos pasos y el enlace a tu portal personal.</p>

            <div class="summary">
                <div>
                    <strong>Estancia</strong>
                    <p style="margin:.3rem 0 0;">{{ $booking->arrival->format('d/m/Y') }} → {{ $booking->departure->format('d/m/Y') }}</p>
                </div>
                <div>
                    <strong>Viajeros</strong>
                    <p style="margin:.3rem 0 0;">{{ $booking->guests }} personas</p>
                </div>
                <div>
                    <strong>Estado</strong>
                    <p style="margin:.3rem 0 0; text-transform:capitalize;">{{ $booking->status->label() }}</p>
                </div>
            </div>

            <a class="button" href="{{ route('guest.portal.show', $booking->public_access_token) }}">Ir a mi portal</a>
        </div>
    </div>
</body>
</html>
