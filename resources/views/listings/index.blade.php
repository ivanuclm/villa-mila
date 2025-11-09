<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Villa Mila – Alojamientos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu;
            background: #020617;
            color: #e5e7eb;
        }

        .shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
        }

        header {
            margin-bottom: 2rem;
        }

        header h1 {
            margin: 0 0 .25rem;
            font-size: 1.8rem;
        }

        header p {
            margin: 0;
            opacity: .7;
        }

        .grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .card {
            background: #020617;
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(148,163,184,.25);
            box-shadow: 0 18px 45px rgba(0,0,0,.35);
        }

        .card h2 {
            margin: 0 0 .35rem;
            font-size: 1.2rem;
        }

        .card p {
            margin: .15rem 0;
            font-size: .9rem;
        }

        .muted {
            opacity: .75;
            font-size: .85rem;
        }

        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: .35rem;
        }

        .pill {
            padding: .15rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(148,163,184,.45);
            font-size: .75rem;
            opacity: .85;
        }

        .actions {
            margin-top: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .55rem 1rem;
            border-radius: 999px;
            border: 0;
            background: #4f46e5;
            color: #e5e7eb;
            font-size: .9rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn span {
            margin-left: .25rem;
            font-size: .9em;
            opacity: .9;
        }

        .empty {
            padding: 1rem;
            text-align: center;
            border-radius: 12px;
            border: 1px dashed rgba(148,163,184,.35);
            background: rgba(15,23,42,.7);
            font-size: .9rem;
            opacity: .85;
        }
    </style>
</head>
<body>
<div class="shell">
    <header>
        <h1>Villa Mila</h1>
        <p>Alojamientos rurales en Valdeganga y alrededores.</p>
    </header>

    @if ($listings->isEmpty())
        <div class="empty">
            Todavía no hay alojamientos publicados. Vuelve pronto. 🙂
        </div>
    @else
        <div class="grid">
            @foreach ($listings as $listing)
                <article class="card">
                    <h2>{{ $listing->name }}</h2>
                    <p class="muted">
                        {{ $listing->address }}
                        @if($listing->license_number)
                            — Licencia: {{ $listing->license_number }}
                        @endif
                    </p>

                    @php
                        // Descripción muy corta solo para la tarjeta
                        $desc = is_array($listing->description)
                            ? ($listing->description['es'] ?? reset($listing->description))
                            : $listing->description;
                    @endphp

                    @if ($desc)
                        <p>{{ \Illuminate\Support\Str::limit($desc, 120) }}</p>
                    @endif

                    <div class="pill-row">
                        <div class="pill">Hasta {{ $listing->max_guests }} huéspedes</div>
                        {{-- Más adelante podemos añadir "Desde XX €/noche" con datos reales --}}
                    </div>

                    <div class="actions">
                        <a href="{{ route('public.listing.show', $listing) }}" class="btn">
                            Ver calendario y reservar <span>→</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
</body>
</html>
