<x-mail::message>
# Nueva solicitud de {{ $booking->customer_name }}

@if ($coverUrl)
<img src="{{ $coverUrl }}" alt="Portada de {{ $booking->listing->name ?? 'Villa Mila' }}" style="width: 100%; border-radius: 14px; margin-bottom: 16px;">
@endif

Tienes una reserva pendiente para **{{ $booking->listing->name ?? 'Villa Mila' }}**. Revisa los datos y confirma las fechas desde el panel.

<x-mail::panel>
### Datos del huésped
- Nombre: **{{ $booking->customer_name }}**
- Email: **{{ $booking->customer_email }}**
@if(!empty($booking->customer_phone))
- Teléfono: **{{ $booking->customer_phone }}**
@endif
@if(!empty($booking->notes))
- Comentarios: {{ $booking->notes }}
@endif
</x-mail::panel>

<x-mail::panel>
### Reserva solicitada
- Llegada: {{ $booking->arrival->format('d/m/Y') }}
- Salida: {{ $booking->departure->format('d/m/Y') }}
- Huéspedes: {{ $booking->guests }}
- Estado actual: {{ $booking->status->label() }}

### Presupuesto
- Noches: {{ $quote['nights'] }}
- Subtotal: €{{ number_format($quote['subtotal'], 2, ',', '.') }}
- Limpieza: €{{ number_format($quote['cleaning'], 2, ',', '.') }}
- **Total:** €{{ number_format($quote['total'], 2, ',', '.') }}
</x-mail::panel>

@if ($adminUrl)
<x-mail::button :url="$adminUrl">
Abrir en el panel
</x-mail::button>
@endif

@if ($portalUrl)
<x-mail::button :url="$portalUrl" color="secondary">
Ver portal público
</x-mail::button>
@endif

</x-mail::message>
