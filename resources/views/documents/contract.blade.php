<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Contrato de alquiler - {{ $listing->name ?? 'Villa Mila' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; color: #111; }
        h1, h2, h3 { margin-bottom: .3rem; }
        p { margin: .4rem 0; }
        .section { margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: .5rem; font-size: 11px; }
        th, td { border: 1px solid #ccc; padding: .2rem .4rem; }
        ul { padding-left: 1rem; }
        li { margin-bottom: .3rem; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>Contrato de alquiler turístico de corta duración</h1>
    <p class="muted">Generado el {{ $generatedAt->format('d/m/Y H:i') }}</p>

    <div class="section">
        <p><strong>Alojamiento:</strong> {{ $listing->name ?? 'Villa Mila' }} ({{ $listing->address ?? 'Valdeganga, Albacete' }})</p>
        <p><strong>Capacidad:</strong> 8 personas (hasta 11 con supletorias / cuna)</p>
        <p><strong>Referencia catastral:</strong> 4732001XJ1343S0001YF</p>
        <p><strong>Nº de registro turístico:</strong> 02012120657</p>
    </div>

    <div class="section">
        <h2>Partes que intervienen</h2>
        <p><strong>Propietaria:</strong> {{ config('villa.owner_name', 'Milagros') }} ({{ config('villa.owner_phone') }})</p>
        <p><strong>Representante de los viajeros:</strong> {{ $booking->customer_name }} · {{ $booking->customer_email }} @if($booking->customer_phone) · {{ $booking->customer_phone }} @endif</p>
        <p><strong>Código de reserva:</strong> {{ $booking->id }} · Fecha de reserva {{ optional($booking->created_at)->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Periodo de alquiler</h2>
        <p>Desde el {{ $booking->arrival?->format('d/m/Y') }} a las 12:00 h hasta el {{ $booking->departure?->format('d/m/Y') }} a las 12:00 h.</p>
        <p><strong>Noches:</strong> {{ $booking->arrival && $booking->departure ? $booking->arrival->diffInDays($booking->departure) : '—' }}</p>
        <p><strong>Precio total:</strong> €{{ number_format($booking->total ?? 0, 2, ',', '.') }} (pago {{ $booking->payment_method ? 'mediante ' . $booking->payment_method : 'según acuerdo' }})</p>
    </div>

    <div class="section">
        <h2>Listado de viajeros</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Documento</th>
                    <th>Nacionalidad</th>
                    <th>Fecha de nacimiento</th>
                    <th>Contacto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $index => $guest)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $guest->full_name }}</td>
                        <td>{{ $guest->document_number }}</td>
                        <td>{{ $guest->nationality }}</td>
                        <td>{{ optional($guest->birthdate)->format('d/m/Y') }}</td>
                        <td>{{ $guest->email ?? '' }} {{ $guest->phone ? ' / ' . $guest->phone : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Aún no se han registrado viajeros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Condiciones principales</h2>
        <p>La reserva se rige por la normativa LAU 4/2013, LSC 4/2015, RC 88/2018, RC 933/2021 y RC 1312/2024, así como por las normas internas de Villa Mila:</p>
        <ul>
            <li>Uso exclusivo del alojamiento. Prohibido el acceso a personas o mascotas no registradas.</li>
            <li>Mínimo 2 noches y 8 viajeros. Máximo 11 viajeros (incluyendo supletorias y cuna).</li>
            <li>Fianza de 250 € (no limitativa) que se devuelve en un máximo de 3 días si no hay daños.</li>
            <li>Política de cancelación: 100% hasta 48 h; 50% hasta 15 días; no reembolsable dentro de 14 días salvo fuerza mayor.</li>
            <li>Solo se admite perro de asistencia documentado.</li>
            <li>El representante responde de la veracidad de los datos y de los daños ocasionados durante la estancia.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Normas generales</h2>
        <ul>
            <li>Respetar el entorno físico y acústico. Horario de descanso 00:00–08:00.</li>
            <li>Uso responsable de barbacoa y paellero; prohibidas bengalas, petardos y cohetes.</li>
            <li>Prohibido fumar o vapear dentro de la casa. Utilizar ceniceros en exteriores.</li>
            <li>No se permiten fiestas estridentes ni despedidas de soltero/a.</li>
            <li>La piscina es de uso exclusivo. Ducharse antes de entrar; prohibido cristal y baño de mascotas.</li>
            <li>Utilizar toallas de piscina específicas y reciclar la basura al salir.</li>
            <li>Prohibido modificar mobiliario, clavar elementos o cargar vehículos eléctricos.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Firmas</h2>
        <table>
            <tr>
                <td>
                    <strong>Representante de viajeros</strong><br>
                    Nombre: {{ $booking->customer_name }}<br>
                    Documento: {{ $guests->first()->document_number ?? '________________' }}<br>
                    Firma: ________________________________
                </td>
                <td>
                    <strong>Propietaria</strong><br>
                    Nombre: {{ config('villa.owner_name', 'Milagros') }}<br>
                    Firma: ________________________________
                </td>
            </tr>
        </table>
        <p class="muted">Ambas partes se someten a los tribunales competentes de Albacete para resolver cualquier disputa relacionada con este contrato.</p>
    </div>
</body>
</html>
