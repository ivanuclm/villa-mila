<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $listing['name'] }} – Casa rural en Valdeganga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --vm-brand: #4f46e5;          /* morado/azul brand */
            --vm-brand-dark: #3730a3;
            --vm-bg: #f5f5f4;
            --vm-text: #111827;
            --vm-muted: #6b7280;
            --vm-radius-lg: 18px;
            --vm-radius-md: 12px;
            --vm-shadow-soft: 0 18px 45px rgba(15,23,42,.18);
        }

        *, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--vm-text);
            background: #fff;
        }

        a { text-decoration: none; color: inherit; }

        /* ========== NAVBAR ========== */

        .navbar {
            position: fixed;
            top:0; left:0; right:0;
            z-index: 30;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 1.1rem 7vw;
            transition: background .25s ease, box-shadow .25s ease, padding .25s ease;
            background: linear-gradient(to bottom, rgba(0,0,0,.35), transparent);
            color:#f9fafb;
        }

        .navbar--scrolled {
            background:#ffffff;
            box-shadow: 0 8px 25px rgba(15,23,42,.10);
            color: #111827;
            padding: .7rem 7vw;
        }

        .navbar__logo {
            font-weight:600;
            letter-spacing:.08em;
            text-transform:uppercase;
            font-size:.9rem;
        }

        .navbar__links {
            display:flex;
            gap:1.75rem;
            font-size:.9rem;
        }

        .navbar__links a {
            position:relative;
        }

        .navbar__links a::after {
            content:"";
            position:absolute;
            left:0; bottom:-4px;
            width:0;
            height:2px;
            background: currentColor;
            border-radius: 999px;
            transition: width .18s ease;
        }

        .navbar__links a:hover::after {
            width:100%;
        }

        .navbar__cta {
            padding:.55rem 1.2rem;
            border-radius:999px;
            background:#f9fafb;
            color:#111827;
            font-size:.85rem;
            font-weight:500;
            border:1px solid rgba(148,163,184,.5);
        }

        .navbar--scrolled .navbar__cta {
            background:var(--vm-brand);
            color:#fff;
            border-color:var(--vm-brand-dark);
        }

        /* ========== HERO ========== */

        .hero {
            position:relative;
            min-height:100vh;
            color:#f9fafb;
        }

        .hero__bg {
            position:absolute;
            inset:0;
            background-size:cover;
            background-position:center;
            filter:brightness(.7); /* oscurecer un poco la imagen, USAR EN CONJUNTO DEL BACKGROUND DE DESPUES */
        }

        .hero__overlay {
            position:absolute;
            inset:0;
            /* background: radial-gradient(circle at 20% 0%, rgba(15,23,42,.45), transparent 60%),
                        linear-gradient(to top, rgba(15,23,42,.75), transparent 55%); */
        }

        .hero__content {
            position:relative;
            z-index:10;
            max-width: 1120px;
            margin:0 auto;
            padding: 30vh 7vw 4rem;
        }

        .hero__eyebrow {
            font-size:.8rem;
            letter-spacing:.18em;
            text-transform:uppercase;
            color: #e5e7eb;
        }

        .hero__title {
            margin-top:.75rem;
            font-size: clamp(2.2rem, 4vw, 3.3rem);
            line-height:1.05;
            max-width: 18ch;
        }

        .hero__subtitle {
            margin-top:1rem;
            max-width: 32rem;
            font-size: .98rem;
            color:#e5e7eb;
        }

        .hero__cta-row {
            margin-top:1.8rem;
            display:flex;
            flex-wrap:wrap;
            gap:.75rem;
            align-items:center;
        }

        .btn-primary {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:.85rem 1.6rem;
            border-radius:999px;
            background:var(--vm-brand);
            color:#fff;
            border:none;
            font-size:.95rem;
            font-weight:500;
            box-shadow:0 18px 30px rgba(79,70,229,.45);
            cursor:pointer;
        }
        .btn-primary:hover { background:var(--vm-brand-dark); }

        .btn-ghost {
            padding:.7rem 1.2rem;
            border-radius:999px;
            border:1px solid rgba(249,250,251,.55);
            background:rgba(15,23,42,.35);
            font-size:.85rem;
            color:#e5e7eb;
        }

        .hero__meta {
            margin-top:1.5rem;
            display:flex;
            gap:1.5rem;
            flex-wrap:wrap;
            font-size:.8rem;
            color:#d1d5db;
        }

        /* ========== SECCIONES MAIN ========== */

        main {
            background:#fff;
        }

        .section {
            max-width:1120px;
            margin:0 auto;
            padding:4rem 7vw;
        }

        .section--alt {
            background: var(--vm-bg);
        }

        .section__title {
            font-size:1.7rem;
            margin-bottom: .75rem;
        }

        .section__kicker {
            font-size:.8rem;
            letter-spacing:.2em;
            text-transform:uppercase;
            color:var(--vm-muted);
            margin-bottom:.3rem;
        }

        .section__cols {
            display:grid;
            gap:2rem;
            grid-template-columns: minmax(0, 3fr) minmax(0, 2.5fr);
            align-items:start;
        }

        .text-muted {
            color:var(--vm-muted);
            font-size:.95rem;
            line-height:1.7;
        }

        .stats-row {
            margin-top:1.5rem;
            display:flex;
            flex-wrap:wrap;
            gap:1.5rem;
            font-size:.9rem;
        }

        .stat {
            min-width: 120px;
        }
        .stat strong {
            display:block;
            font-size:1.2rem;
        }
        .stat span {
            color:var(--vm-muted);
            font-size:.8rem;
            text-transform:uppercase;
            letter-spacing:.12em;
        }

        /* ========== GALERÍA SIMPLE ========== */

        .gallery-strip {
            display:flex;
            gap:1rem;
            overflow-x:auto;
            scroll-snap-type:x mandatory;
            padding-bottom:.5rem;
        }
        .gallery-strip::-webkit-scrollbar { height:6px; }
        .gallery-strip::-webkit-scrollbar-thumb {
            background:rgba(148,163,184,.7);
            border-radius:999px;
        }

        .gallery-item {
            flex:0 0 260px;
            height:180px;
            border-radius: var(--vm-radius-md);
            overflow:hidden;
            scroll-snap-align:start;
            background:#e5e5e5;
            box-shadow:0 10px 25px rgba(15,23,42,.15);
        }
        .gallery-item img{
            width:100%; height:100%; object-fit:cover;
            display:block;
        }

        /* ========== CÓMO LLEGAR ========== */

        .map-card {
            margin-top:1.5rem;
            border-radius: var(--vm-radius-lg);
            overflow:hidden;
            box-shadow:var(--vm-shadow-soft);
            background:#e5e7eb;
            min-height:260px;
        }

        .map-card iframe {
            width:100%;
            height:320px;
            border:0;
            display:block;
        }

        /* ========== FOOTER ========== */

        footer {
            border-top:1px solid #e5e7eb;
            padding:1.5rem 7vw 2rem;
            font-size:.8rem;
            color:var(--vm-muted);
        }

        footer div {
            max-width:1120px;
            margin:0 auto;
            display:flex;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:.75rem;
        }

        /* ========== RESPONSIVE ========== */

        @media (max-width: 900px) {
            .hero__content { padding-top: 26vh; }
            .section__cols { grid-template-columns: minmax(0,1fr); }
            .navbar__links { display:none; }
        }

        @media (max-width: 640px) {
            .hero__content { padding-top: 22vh; }
            .hero__title { font-size:2rem; }
        }
    </style>
</head>
<body>

<header class="navbar" id="navbar">
    <div class="navbar__logo">{{ $listing['name'] }}</div>
    <nav class="navbar__links">
        <a href="#sobre">La casa</a>
        <a href="#galeria">Galería</a>
        <a href="#entorno">Entorno</a>
        <a href="#como-llegar">Cómo llegar</a>
    </nav>
    <a class="navbar__cta" href="{{ route('public.booking') }}">
        Reservar ahora
    </a>
</header>

<section class="hero" id="inicio">
    <div class="hero__bg" style="background-image:url('{{ $heroImage }}')"></div>
    <div class="hero__overlay"></div>

    <div class="hero__content">
        <div class="hero__eyebrow">
            {{ $listing['address'] }}
        </div>
        <h1 class="hero__title">
            Descubre la esencia de Villa Mila en Valdeganga.
        </h1>
        <p class="hero__subtitle">
            Una casa rural completa para disfrutar con familia o amigos, a un paso de Albacete
            y rodeada de naturaleza, silencio y cielo abierto.
        </p>

        <div class="hero__cta-row">
            <a class="btn-primary" href="{{ route('public.booking') }}">
                Reservar ahora
            </a>
            <a class="btn-ghost" href="#sobre">
                Ver más sobre la casa
            </a>
        </div>

        <div class="hero__meta">
            <span>Capacidad hasta {{ $listing['max_guests'] }} huéspedes</span>
            @if(!empty($listing['license_number']))
                <span>Licencia: {{ $listing['license_number'] }}</span>
            @endif
        </div>
    </div>
</section>

<main>
    {{-- SOBRE LA CASA --}}
    <section class="section" id="sobre">
        <div class="section__cols">
            <div>
                <p class="section__kicker">UNA CASA RURAL ENTERA PARA TI</p>
                <h2 class="section__title">Un refugio tranquilo para desconectar sin alejarte de la ciudad.</h2>
                <p class="text-muted">
                    @if(is_array($listing['description']))
                        {{ $listing['description']['es'] ?? reset($listing['description']) }}
                    @else
                        {{ $listing['description'] }}
                    @endif
                </p>

                <div class="stats-row">
                    <div class="stat">
                        <strong>Hasta {{ $listing['max_guests'] }} personas</strong>
                        <span>Capacidad</span>
                    </div>
                    <div class="stat">
                        <strong>Casa completa</strong>
                        <span>Uso exclusivo</span>
                    </div>
                    <div class="stat">
                        <strong>Valdeganga</strong>
                        <span>Ribera del Júcar</span>
                    </div>
                </div>
            </div>

            <div>
                <p class="section__kicker">VISTAZO RÁPIDO</p>
                <p class="text-muted">
                    Ideal para escapadas en familia, fines de semana con amigos o pequeñas celebraciones.
                    Piscina en verano, zonas exteriores para comer al aire libre y un interior acogedor
                    durante todo el año.
                </p>
                <ul class="text-muted" style="margin-top:1rem; padding-left:1.1rem;">
                    <li>✔&nbsp; Piscina privada de temporada</li>
                    <li>✔&nbsp; Cocina equipada y barbacoa</li>
                    <li>✔&nbsp; Salón amplio con chimenea</li>
                    <li>✔&nbsp; Wifi y calefacción/aire acondicionado</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- GALERÍA --}}
    <section class="section section--alt" id="galeria">
        <p class="section__kicker">GALERÍA</p>
        <h2 class="section__title">Imagina tus días aquí.</h2>
        <p class="text-muted" style="margin-bottom:1.5rem;">
            Un vistazo rápido a algunos rincones de la casa y su entorno. Más adelante
            conectaremos esto con la galería gestionada desde el panel.
        </p>

        <div class="gallery-strip" id="galleryStrip">
            {{-- De momento, imágenes estáticas. Sustituye las URLs por tus fotos reales --}}
            <div class="gallery-item">
                <img src="/images/EXTERIORdia2.jpg" alt="Piscina y terraza de Villa Mila">
            </div>
            <div class="gallery-item">
                <img src="/images/EXTERIORdia3.jpg" alt="Zona exterior con mesa y sillas">
            </div>
            <div class="gallery-item">
                <img src="/images/EXTERIORnoche.jpg" alt="Habitación principal">
            </div>
            <div class="gallery-item">
                <img src="/images/PUERTAplaca.jpg" alt="Vista del entorno natural">
            </div>
            <div class="gallery-item">
                <img src="/images/VistasVERDE.jpg" alt="Detalle del salón">
            </div>
        </div>
    </section>

    {{-- ENTORNO / CALL TO ACTION --}}
    <section class="section" id="entorno">
        <div class="section__cols">
            <div>
                <p class="section__kicker">ENTORNO</p>
                <h2 class="section__title">Naturaleza, río Júcar y pueblos con encanto alrededor.</h2>
                <p class="text-muted">
                    Valdeganga está rodeada de senderos, miradores y rutas junto al río Júcar. Perfecto
                    para caminar, ir en bici o simplemente dejar pasar el tiempo entre campo y silencio.
                </p>
            </div>
            <div>
                <p class="section__kicker">LISTO PARA RESERVAR</p>
                <p class="text-muted">
                    Consulta disponibilidad y calcula tu precio en tiempo real desde nuestra página de
                    reservas. Sin intermediarios ni comisiones extra.
                </p>
                <a class="btn-primary" href="{{ route('public.listing.show', $listing['slug']) }}#reserva"
                   style="margin-top:1.2rem; display:inline-flex;">
                    Ir a la reserva
                </a>
            </div>
        </div>
    </section>

    {{-- CÓMO LLEGAR --}}
    <section class="section section--alt" id="como-llegar">
        <p class="section__kicker">SÓLO A UN RATO DE ALBACETE</p>
        <h2 class="section__title">¿Cómo llegar?</h2>
        <p class="text-muted">
            Villa Mila se encuentra en Valdeganga (Albacete). Aquí más adelante podemos incrustar
            un mapa interactivo con tu ubicación exacta y un pequeño texto con indicaciones.
        </p>

        <div class="map-card">
            {{-- TODO: sustituir por tu iframe real de Google Maps / Leaflet más adelante --}}
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox=-1.70%2C39.10%2C-1.66%2C39.13&layer=mapnik"
                loading="lazy"
            ></iframe>
        </div>
    </section>
</main>

<footer>
    <div>
        <span>© {{ date('Y') }} {{ $listing['name'] }}. Todos los derechos reservados.</span>
        <span>Próximamente: Aviso legal · Privacidad · Cookies</span>
    </div>
</footer>

<script>
    // Navbar que cambia al hacer scroll
    const navbar = document.getElementById('navbar');
    const heroHeight = window.innerHeight * 0.55;

    function onScroll() {
        if (window.scrollY > heroHeight) {
            navbar.classList.add('navbar--scrolled');
        } else {
            navbar.classList.remove('navbar--scrolled');
        }
    }
    window.addEventListener('scroll', onScroll);
    onScroll();

    // Auto-scroll muy suave de la galería (se puede quitar si no te gusta)
    const strip = document.getElementById('galleryStrip');
    if (strip) {
        let offset = 0;
        setInterval(() => {
            if (strip.scrollWidth <= strip.clientWidth) return;
            offset += 260 + 16; // ancho aproximado + gap
            if (offset > strip.scrollWidth) offset = 0;
            strip.scrollTo({ left: offset, behavior: 'smooth' });
        }, 5000);
    }
</script>
</body>
</html>
