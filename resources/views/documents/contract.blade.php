<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Contrato de alquiler - {{ $listing->name ?? 'Villa Mila' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11.5px; line-height: 1.5; color: #111; margin: 24px; }
        h1, h2, h3 { margin: 0 0 .4rem; text-transform: uppercase; }
        h1 { font-size: 18px; }
        h2 { font-size: 14px; border-bottom: 1px solid #333; padding-bottom: .15rem; }
        h3 { font-size: 12px; margin-top: .8rem; }
        p { margin: .35rem 0; text-align: justify; }
        ul { margin: .3rem 0 .6rem 1.2rem; }
        li { margin-bottom: .2rem; }
        table { width: 100%; border-collapse: collapse; margin: .5rem 0 1rem; }
        th, td { border: 1px solid #bbb; padding: .25rem .35rem; font-size: 11px; vertical-align: top; }
        .muted { color: #666; }
        .two-col { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .35rem .85rem; }
        .signature-block { height: 90px; }
    </style>
</head>
@php
    use Illuminate\Support\Facades\Storage;
    $ownerName = $listing->contract_owner_name ?? config('villa.owner_name', 'Milagros');
    $ownerDocument = $listing->contract_owner_document ?? 'DNI ____________________';
    $ownerAddress = $listing->contract_owner_address ?? 'C/ Zamora nº 4 · 7º izq. B · 02001 Albacete';
    $listingAddress = $listing->address ?? 'Calle Olmo 39 / Calle Abadía 55 · Valdeganga (Albacete) · CP 02150';
    $guestCount = max($guests->count(), 11);
    $contractText = $listing->contract_full_text ?? config('villa.contract_template');
    $cadastral = $listing->cadastral_reference ?? '4732001XJ1343S0001YF';
    $municipalCode = $listing->municipal_registry_code ?? 'D4AA ACQC 34Y7 CHDH H43M';
    $clmRegistry = $listing->clm_registry_number ?? '02012120657 · Casa Rural tres estrellas verdes';
    $nraRegistry = $listing->nra_registry_number ?? 'ESFCTU0000020040007853470000000000000TURO1-02050/18095';
    $ccaaLicense = $listing->ccaa_license_code ?? 'TURO1 - 0250 / 1809';
    $baseCapacity = $listing->max_guests ?? 0;
    $extraBeds = $listing->extra_beds ?? 0;
    $cribs = $listing->cribs ?? 0;
    $totalBeds = $baseCapacity + $extraBeds;
    $documentTypes = [
        'dni' => 'DNI',
        'nie' => 'NIE',
        'passport' => 'Pasaporte',
        'other' => 'Otro documento',
    ];
    $genderOptions = [
        'M' => 'Mujer',
        'H' => 'Hombre',
        'X' => 'Otro / No binario',
    ];
    $paymentMethodLabels = [
        'bank_transfer' => 'Transferencia bancaria inmediata / banca online',
        'cash' => 'Efectivo',
        'card' => 'TPV / tarjeta',
        'other' => 'Otro método acordado',
    ];
    $chosenPaymentMethod = $paymentMethodLabels[$booking->payment_method] ?? null;
    $representativeFullName = trim(implode(' ', array_filter([
        $booking->customer_first_name,
        $booking->customer_first_surname,
        $booking->customer_second_surname,
    ])));
    $representativeDocumentType = $documentTypes[$booking->customer_document_type] ?? $booking->customer_document_type;
@endphp
<body>
    <h1>Contrato de alquiler turístico de corta duración</h1>
    <p class="muted">
        Casa Rural {{ $listing->name ?? 'Villa Mila de Valdeganga' }} · Documento generado el {{ $generatedAt->format('d/m/Y H:i') }}
    </p>
    <br>
    <h2>Identificación del alojamiento</h2>
    <div class="two-col">
        <p><strong>Dirección completa:</strong> {{ $listingAddress }}</p>
        <p><strong>La Casa Rural Villa Mila es un ALOJAMIENTO.</strong></p>
        <p><strong>Capacidad base:</strong> {{ $baseCapacity ?: '___' }} viajeros. <strong>Camas supletorias:</strong> {{ $extraBeds }}. <strong>Cunas:</strong> {{ $cribs }} (no computan en el total de plazas). <strong>Capacidad total:</strong> {{ $totalBeds }} plazas + {{ $cribs }} cuna(s).</p>
        <p><strong>Licencia VUT:</strong> {{ $listing->license_number ?? $ccaaLicense }}</p>
        <p><strong>Registro CLM (empresas y establecimientos turísticos):</strong> {{ $clmRegistry }}</p>
        <p><strong>NRA – Registro único de arrendamiento oficial:</strong> {{ $nraRegistry }}</p>
        <p><strong>Nº licencia CCAA (TURO):</strong> {{ $ccaaLicense }}</p>
        <p><strong>Referencia catastral:</strong> {{ $cadastral }}</p>
        <p><strong>Código seguro de verificación municipal:</strong> {{ $municipalCode }}</p>
    </div>
    <br>
    <h2>Partes que intervienen</h2>
    <p><strong>En Valdeganga a:</strong> {{ $generatedAt->isoFormat('DD [de] MMMM [de] YYYY') }}</p>
    <p><strong>PROPIETARIO:</strong> {{ $ownerName }}; mayor de edad, con domicilio a efecto de notificaciones en {{ $ownerAddress }}; documento {{ $ownerDocument }}.</p>
    <p><strong>DATOS DEL QUE RESERVA en su nombre y en representación de los viajeros:</strong></p>
    <div class="two-col">
        <p><strong>Nombre:</strong> {{ $booking->customer_first_name ?? $booking->customer_name ?? '________________________' }}</p>
        <p><strong>Primer apellido:</strong> {{ $booking->customer_first_surname ?? '________________' }}</p>
        <p><strong>Segundo apellido:</strong> {{ $booking->customer_second_surname ?? '________________' }}</p>
        <p><strong>Email / Teléfono:</strong> {{ $booking->customer_email ?? '________________' }} {{ $booking->customer_phone ? '· '.$booking->customer_phone : '' }}</p>
        <p><strong>Tipo y nº de documento:</strong> {{ $representativeDocumentType ?? '______' }} · {{ $booking->customer_document_number ?? '________________' }}</p>
        <p><strong>Nº de soporte:</strong> {{ $booking->customer_document_support_number ?? '________________' }}</p>
        <p><strong>Fecha y país de nacimiento:</strong> {{ optional($booking->customer_birthdate)->format('d/m/Y') ?? '____ / ____ / ______' }} · {{ $booking->customer_birth_country ?? '________________' }}</p>
        <p><strong>Domicilio habitual:</strong> C/ {{ $booking->customer_address_street ?? '________________' }} Nº {{ $booking->customer_address_number ?? '____' }} · {{ $booking->customer_address_city ?? 'Ciudad __________' }} · Provincia {{ $booking->customer_address_province ?? '__________' }} · País {{ $booking->customer_address_country ?? '_____________' }}.</p>
        <p><strong>A efectos de facturación:</strong> Domicilio {{ $booking->customer_address_street ?? '________________' }} · Población {{ $booking->customer_address_city ?? '____________' }} · Provincia {{ $booking->customer_address_province ?? '____________' }} · País de facturación: ESPAÑA.</p>
    </div>
    <p><strong>Código de reserva:</strong> {{ $booking->public_access_token ?? $booking->id }} (coincide con la fecha de abono de la reserva-fianza). Fecha de solicitud: {{ optional($booking->created_at)->format('d/m/Y') ?? '____/____/____' }}.</p>

    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Marco legal y normas generales</h2>
    <p>Las normas de Villa Mila de Valdeganga se basan en las siguientes leyes y reales decretos:</p>
    <ul>
        <li>LAU 4/2013, de 4 de junio.</li>
        <li>LSC 4/2015, de 30 de marzo.</li>
        <li>RC 88/2018, de 29 de noviembre.</li>
        <li>RC 933/2021, de 26 de octubre.</li>
        <li>RC 1312/2024, de 23 de diciembre.</li>
    </ul>
    <p>Villa Mila de Valdeganga (Albacete) es un alojamiento no compartido, de uso exclusivo. Se prohíbe acceder a personas y mascotas no incluidas en este contrato; el propietario informará a las autoridades en caso de incumplimiento. Invitados no registrados (incluidos menores) no están cubiertos por el seguro; los daños ocasionados se facturarán aparte. Todos los niños menores de tres años y bebés deben registrarse, aunque no ocupen cama.</p>
    <p>El representante declara la veracidad de los datos y asume la responsabilidad administrativa o sancionadora derivada de cualquier incumplimiento.</p>
    <br>
    <h2>Reserva, fianza y periodo contratado</h2>
    <ul>
        <li><strong>Reserva inmediata:</strong> hasta 2 días antes de la fecha de entrada (consultar disponibilidad en calendario). ENTRE LA SALIDA DE VIAJEROS ANTERIORES Y VUESTRA ENTRADA HAY QUE DEJAR DOS DÍAS.</li>
        <li><strong>Reserva con antelación:</strong> se puede reservar hasta 6 meses antes de la fecha de llegada.</li>
        <li><strong>Reserva previo pago de 250 €:</strong> sirve también como FIANZA NO LIMITATIVA para cubrir posibles daños, uso indebido del recinto o incumplimientos del contrato.</li>
        <li><strong>Devolución de fianza:</strong> si no hay daños se devolverá en un plazo máximo de 3 días tras la salida.</li>
        <li><strong>Daños o incumplimientos:</strong> si existieran, se detallarán por factura aparte en un plazo máximo de 14 días posteriores a la salida, teniendo en cuenta la cuantía de la fianza.</li>
    </ul>
    <p><strong>Periodo reservado de noches y días que se alquilan:</strong> Desde el día {{ $booking->arrival?->format('d') ?? '____' }} de {{ $booking->arrival?->translatedFormat('F \\d\\e Y') ?? '________________' }} a las 12 h AM, hasta el {{ $booking->departure?->format('d') ?? '____' }} de {{ $booking->departure?->translatedFormat('F \\d\\e Y') ?? '________________' }} a las 12 h AM. Son {{ $booking->arrival && $booking->departure ? $booking->arrival->diffInDays($booking->departure) : '____' }} noches.</p>
    <p><strong>Precio:</strong> {{ $booking->total ? '€'.number_format($booking->total, 2, ',', '.') : '________________________' }}. SE PAGARÁ el día de la entrada según acuerden las partes.</p>
    <p>Villa Mila de Valdeganga (Albacete) tiene Seguro de Responsabilidad Civil. Queda prohibido al viajero ceder o subarrendar el alojamiento.</p>
    <br>
    <h2>Política de cancelación</h2>
    <ul>
        <li>Cancelación dentro de las 48 horas posteriores a la reserva: reembolso total.</li>
        <li>Cancelación entre 48 horas y 15 días antes de la entrada: se devuelve el 50%.</li>
        <li>Cancelación con 14 días o menos antes de la entrada: no se devuelve la reserva.</li>
        <li>Cancelaciones por fuerza mayor debidamente documentada: devolución total.</li>
        <li>Si el propietario cancela por fuerza mayor, devolverá el total, intentará encontrar alojamiento similar u ofrecer otras fechas.</li>
    </ul>

    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Proceso de entrada</h2>
    <p>Dos días antes del check-in se enviará un enlace para registrar los datos de todos los viajeros; el formulario se remite automáticamente al Ministerio del Interior y remite un aviso de envío correcto. Las llaves se entregan tras el pago total de la estancia. Serán recibidos personalmente; el alojamiento dispone de caja de seguridad con contraseña. El horario de entrada puede ser flexible si se acuerda con al menos 24 h de antelación. Adelantar la entrada o retrasar la salida puede suponer un coste extra sujeto a disponibilidad.</p>
    <br>
    <h2>Registro documental de personas físicas o jurídicas</h2>
    <p>El propietario está obligado a registrar los datos identificativos de todos los viajeros (incluidos niños y bebés). A continuación se listan los huéspedes registrados y espacios adicionales para completar hasta el máximo permitido:</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Primer apellido</th>
                <th>Segundo apellido</th>
                <th>Fecha de nacimiento / menor / parentesco</th>
                <th>Documento · Nº soporte · Sexo · País de nacimiento</th>
                <th>Firma del viajero</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $index => $guest)
                @php
                    $parts = ($guest?->full_name) ? preg_split('/\s+/', $guest->full_name) : [];
                    $firstName = $guest?->first_name ?? array_shift($parts) ?? '';
                    $firstSurname = $guest?->first_surname ?? array_shift($parts) ?? '';
                    $secondSurname = $guest?->second_surname ?? implode(' ', $parts);
                    $birthdate = $guest?->birthdate ? $guest->birthdate->format('d/m/Y') : '—';
                    $docType = $guest?->document_type ? ($documentTypes[$guest->document_type] ?? strtoupper($guest->document_type)) : null;
                    $docNumber = $guest?->document_number ?? '________________';
                    $supportNumber = $guest?->document_support_number ?? '________________';
                    $genderLabel = $guest?->gender ? ($genderOptions[$guest->gender] ?? $guest->gender) : '_______';
                    $nationality = $guest?->nationality ?? '—';
                    $birthCountry = $guest?->birth_country ?? '—';
                    $isMinor = $guest?->is_minor ?? false;
                    $kinship = $guest?->kinship ?? null;
                    $signatureData = null;
                    if ($guest?->signature_path && Storage::disk('public')->exists($guest->signature_path)) {
                        $rawSignature = Storage::disk('public')->get($guest->signature_path);
                        $extension = strtolower(pathinfo($guest->signature_path, PATHINFO_EXTENSION));
                        $mime = $extension === 'png' ? 'image/png' : 'image/jpeg';
                        $signatureData = 'data:' . $mime . ';base64,' . base64_encode($rawSignature);
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $firstName }}</td>
                    <td>{{ $firstSurname }}</td>
                    <td>{{ $secondSurname }}</td>
                    <td>{{ $birthdate }} {{ $isMinor ? '· Menor' : '' }} {{ $kinship ? '· '.$kinship : '' }} · País: {{ $birthCountry }}</td>
                    <td>
                        Documento: {{ $docType ? $docType . ' · ' : '' }}{{ $docNumber }}<br>
                        Nº soporte: {{ $supportNumber }} · Sexo: {{ $genderLabel }}<br>
                        País nacimiento: {{ $nationality }}
                    </td>
                    <td style="text-align:center;">
                        @if($signatureData)
                            <img src="{{ $signatureData }}" alt="Firma de {{ $firstName }} {{ $firstSurname }}" style="max-width:120px; max-height:60px;">
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#666;">Aún no se han registrado datos de viajeros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Documentación del perro de asistencia</h2>
    <p>Solo se admite un perro de asistencia con su documentación. Debe traer cama y platos propios, hacer sus necesidades fuera del alojamiento y cumplir las normas de convivencia. No puede bañarse en la piscina. Debe llevar chip y cartilla de vacunación al día. Los daños ocasionados serán imputables al dueño, que exime de responsabilidad a la propiedad; la fianza, no limitativa, se aplicará para subsanar dichos daños.</p>
    <br>
    <h2>Proceso de salida</h2>
    <p>El contrato se resuelve automáticamente en la fecha y hora indicadas. Retrasar la salida requiere comunicarlo con 24 h de antelación y puede generar coste adicional. Si el viajero abandona el alojamiento antes, deberá comunicarlo y no tendrá derecho a reembolso. Las llaves deben devolverse sin demora al propietario o en el lugar acordado. Se considera falta grave no devolver las llaves ni abandonar el recinto al expirar el contrato; en tal caso se abonará una cantidad igual al triple del precio diario por cada día adicional hasta recuperar la posesión, independientemente de la reserva-fianza.</p>
    
    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Normas generales de la casa</h2>
    <ul>
        <li>Respetar el entorno físico interior y exterior.</li>
        <li><strong>Evitar incendios:</strong> la barbacoa solo se usa con carbón (se facilita una bolsa); no encender si el viento supera 20 km/h. Se aportan cartones para proteger el suelo y el paellero móvil.</li>
        <li>Fumar o vapear está prohibido en el interior. Se permite en el porche o zonas alejadas del monte utilizando ceniceros. Prohibido tirar colillas fuera de los ceniceros.</li>
        <li>Prohibido el uso de bengalas, petardos y cohetes.</li>
        <li>Prohibido tirar basura al monte. Reciclar utilizando las bolsas de repuesto disponibles.</li>
        <li>Respetar el entorno acústico: horario de descanso de 00:00 a 08:00. Moderar música y ruidos.</li>
        <li>Prohibidas fiestas estridentes y comportamientos que alteren la tranquilidad o molesten a los vecinos (podrán avisar a las autoridades).</li>
        <li>Los grupos deben tener una media mínima de 25 años. Padres/tutores responden por sus hijos.</li>
        <li>El perro es responsabilidad de su dueño.</li>
        <li>No usar pelotas o balones dentro de la casa.</li>
        <li>Prohibidos patines, patinetes y patinetes eléctricos en el recinto; no se pueden recargar vehículos eléctricos.</li>
        <li>Prohibido usar confetis en todo el recinto.</li>
        <li>No se admiten despedidas de soltero/a ni fiestas que puedan ocasionar daños.</li>
        <li>No mover muebles ni modificar su disposición original. Prohibido subirse a los muebles o sentarse en ellos y en las camas con ropa mojada. No dejar ropa mojada sobre muebles o camas.</li>
    </ul>
    <br>
    <h2>Piscina</h2>
    <p>La piscina es desbordante y automática; se rellena y depura sola, cuenta con flotador con cloro de refuerzo y un pozo de compensación con tapa amarilla que no debe pisarse. Está disponible desde finales de mayo hasta la primera semana de octubre si el tiempo lo permite. No está vallada. Prohibido tirarse de cabeza. En época fría puede cubrirse; no deben pisarla personas ni perros. Ducharse en la ducha solar al entrar y salir. Prohibido usar cristal, vidrio o chapas cerca y dentro de la piscina. No tirar objetos ni colocar pies de sombrilla, sillas u objetos metálicos sobre los gresites. Recoger los pelos en la ducha solar para evitar lodo y desbordamientos. Prohibido el baño de animales.</p>
    <br>
    <h2>Baños</h2>
    <p>Hay dos baños completos y un aseo. Se facilitan dos o tres rollos de papel higiénico por baño, con extractores. Hay jabón de manos, gel, champú, bolsas de repuesto y secador. Las toallas están sobre las camas; no usarlas para desmaquillarse ni para la piscina (hay toallas específicas). Hay toallas extra en armarios o cajones. No colgar toallas en mamparas ni barras de cortinas; usar las perchas. Hay tendederos interior y exterior para dejarlas a la salida. Evitar atascos: solo papel higiénico al WC; compresas, algodones u otros deben ir al cubo. Recoger los pelos de ducha y bañera. Se proporciona kit de limpieza para emergencias. Traer compresas propias.</p>
    <br>
    <h2>Cocina</h2>
    <p>La cocina está totalmente equipada con electrodomésticos básicos y menaje suficiente. Utilizar hules (disponibles) para proteger las mesas. No golpear los cajones del frigorífico con bolsas de hielo. Usar salvamanteles para proteger del calor. En un armario hay un botiquín revisado antes de cada estancia con los productos básicos permitidos.</p>
    <p><strong>Consumos:</strong> No derrochar energía (calefacción, aire acondicionado, electricidad) ni agua. A la salida sacar la basura a los contenedores de reciclaje situados a 30 m de la casa.</p>
    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Descripción detallada del alojamiento</h2>
    <p>Dispone de calefacción, aire acondicionado, extractores en baños, mosquiteras, bloqueadores de puertas en dormitorios (precaución con niños), luces y señalización de emergencias homologadas, extintores, rejas de protección en la escalera superior y puertas de salida. Hay wifi gratuito en toda la casa (contraseña junto al router en el salón superior) y juegos de mesa, ajedrez y diana.</p>
    <h3>Distribución</h3>
    <p><strong>Plantas:</strong> Dos · Orientación: Este, Sur, Oeste.</p>
    <p><strong>Planta baja:</strong> Aparcamiento para dos coches (aparcamiento público gratuito a 15 m), jardín, piscina privada 8x4 m con ducha solar, barbacoa y paellero móvil (con menaje), cenador y solárium. Gran mirador al monte, valle y río Júcar, pila de piedra y fuente. Salón comedor de 32,5 m² con puerta persiana al jardín, ventilador de techo, estufa de pared con mando y zona de lavadora. Cocina con vistas al jardín y la piscina. Pasillos. Tres dormitorios dobles con camas de 90 cm y vistas a la piscina, uno con salida directa al jardín. Dormitorio principal con cama de 150 cm, vestidor, ventilador de pie y cuna. Baño completo con ducha con asiento y bidé. Segundo baño completo con bañera, ducha, bidé y bañera para bebés (bajo petición). Plancha y secador. Escalera con barandilla abatible para seguridad infantil.</p>
    <p><strong>Planta alta:</strong> Distribuidor y salón comedor de 44 m² con sofá cama de 150 cm y grandes vistas, terraza de 33 m², aseo con ventana, estudio privado con aire acondicionado inverter que funciona como quinto dormitorio (dos camas supletorias de 90 cm), despacho para teletrabajo, cocina totalmente equipada, mesa de comedor y patio. Puede mantenerse cerrado por eficiencia energética; se abre bajo petición con un coste equivalente a 2 personas (sin coste si vienen 10 viajeros). No se alquila de forma independiente. Dispone de recibidor con puerta de reja para seguridad infantil y terraza de 16 m² con escalera al jardín, piscina y calle. Hay otra cama supletoria de 90 cm que se puede ubicar donde se prefiera abonando el precio por persona y noche. El salón tiene chimenea que actualmente no se puede usar hasta disponer de puerta; está prohibido cocinar o asar en ella. La casa cuenta con dos entradas.</p>
    <br>
    <h2>Ubicación y servicios complementarios</h2>
    <p>Situada en la montaña que acoge el valle del río Júcar, a 10 minutos andando de la plaza y 25 km de Albacete. Los servicios sanitarios podrán acceder siempre que el aparcamiento del alojamiento esté libre. Dentro de la casa hay información sobre el pueblo, actividades, tiendas, urgencias y todo tipo de servicios: teléfonos, pedidos, ocio, restaurantes, pubs, taxis, horarios de autobús y servicio de limpieza; se facilitan folletos y enlaces.</p>
    <p>Villa Mila de Valdeganga ofrece alojamiento, información, atención y ayuda 24 h. El resto de servicios correrán a cargo de los viajeros.</p>
    <br>
    <h2>Incidencias y responsabilidades</h2>
    <p>Ante cualquier avería o imprevisto debe contactarse de inmediato con el propietario, quien intentará resolverlo lo antes posible y avisará a servicios técnicos si procede. Los viajeros eximen de responsabilidad a la propiedad en caso de averías o fallos en suministros imputables a empresas proveedoras o fenómenos atmosféricos. El propietario no se responsabiliza de accidentes sufridos por los viajeros. Hay hojas de reclamaciones disponibles. Al finalizar la estancia se revisa el alojamiento y el inventario, tomando fotografías si se detectan desperfectos.</p>
    <p>Los viajeros declaran conocer el estado de conservación y las características del alojamiento y se obligan a conservarlo en perfecto estado durante el plazo pactado.</p>
    <p>Ambas partes se someten expresamente a los tribunales competentes de Albacete para resolver cualquier disputa derivada del contrato.</p>
    <!-- newpage -->
    <div style="page-break-after: always;"></div>
    <h2>Firmas</h2>
    <table class="signature-block">
        <tr>
            <td>
                <strong>Nombre completo y firma del representante y responsable de los viajeros</strong><br><br>
                ____________________________________________
            </td>
            <td>
                <strong>Firma del propietario o representante</strong><br><br>
                ____________________________________________
            </td>
        </tr>
    </table>
    <p>El propietario se reserva el derecho a modificar este contrato por cambios legales, mejoras u otras circunstancias antes de su firma y para futuras contrataciones. El alojamiento cumple la normativa vigente sobre confidencialidad y privacidad de los datos personales de todos los viajeros. El documento consta de cinco folios a doble cara firmados en duplicado por ambas partes.</p>
    <br>
    <br>
    <br>
    <br>
    <h2>Forma de pago</h2>
    <p><strong>Forma acordada:</strong> {{ $chosenPaymentMethod ?? 'Pendiente de confirmar con la propietaria (según acuerdo)' }}.</p>
    <p>Opciones aceptadas: efectivo, transferencia bancaria inmediata/banca online o TPV/tarjeta. Se paga a la llegada y se entregan las llaves tras verificar el importe. Instrucciones específicas: {{ config('villa.payment.instructions') }}.</p>
</body>
</html>
