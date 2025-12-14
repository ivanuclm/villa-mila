<x-mail::message>
# Hemos recibido tu solicitud

@if ($coverUrl)
<img src="{{ $coverUrl }}" alt="Foto de {{ $booking->listing->name ?? 'Villa Mila' }}" style="width: 100%; border-radius: 14px; margin-bottom: 16px;">
@endif

Hola {{ $booking->customer_name }},

Tu estancia en **{{ $booking->listing->name ?? 'Villa Mila' }}** está en estado **Pendiente**. A continuación encontrarás toda la información relevante y un acceso directo a tu portal del huésped.

<x-mail::panel>
**Lo que ocurrirá ahora**

@foreach ($flow['guide']['items'] as $item)
- {{ $item }}
@endforeach
</x-mail::panel>

<x-mail::panel>
### Datos de la reserva

- **Llegada:** {{ $booking->arrival->format('d/m/Y') }}
- **Salida:** {{ $booking->departure->format('d/m/Y') }}
- **Huéspedes:** {{ $booking->guests }}
@if (!empty($booking->customer_phone))
- **Teléfono indicado:** {{ $booking->customer_phone }}
@endif
@if (!empty($booking->notes))
- **Comentarios adicionales:** {{ $booking->notes }}
@endif

### Presupuesto estimado
- Noches: **{{ $quote['nights'] }}**
- Subtotal: **€{{ number_format($quote['subtotal'], 2, ',', '.') }}**
- Limpieza: **€{{ number_format($quote['cleaning'], 2, ',', '.') }}**
- **Total:** €{{ number_format($quote['total'], 2, ',', '.') }}
</x-mail::panel>

@if ($flow['showPaymentInstructions'] && !empty($flow['payment']['bank_account']))
<x-mail::panel>
### Datos bancarios
- Titular: {{ $flow['payment']['account_name'] ?? $flow['ownerName'] }}
- IBAN: {{ $flow['payment']['bank_account'] }}
@if (!empty($flow['payment']['instructions']))
- Nota: {{ $flow['payment']['instructions'] }}
@endif
</x-mail::panel>
@endif

@if ($portalUrl)
<x-mail::button :url="$portalUrl">
Abrir mi portal
</x-mail::button>
@endif

Si necesitas contactar con nosotros puedes escribir a **{{ $flow['ownerEmail'] ?? 'hola@villamila.com' }}** o llamar al **{{ $flow['ownerPhone'] ?? 'teléfono habitual' }}**.

Gracias por reservar de forma directa,  
{{ $flow['ownerName'] }}
</x-mail::message>
