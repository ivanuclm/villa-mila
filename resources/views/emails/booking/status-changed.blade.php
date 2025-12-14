<x-mail::message>
# {{ $isOwner ? 'Actualización de estado' : 'Tu reserva ha cambiado de estado' }}

@if ($coverUrl)
<img src="{{ $coverUrl }}" alt="Imagen del alojamiento" style="width: 100%; border-radius: 14px; margin-bottom: 16px;">
@endif

@if($previousStatus)
Estado anterior: **{{ ucfirst($previousStatus) }}**
@endif

@if($isOwner)
La reserva de **{{ $booking->customer_name }}** está ahora en estado **{{ $booking->status->label() }}**.
@else
Tu estancia en **{{ $booking->listing->name ?? 'Villa Mila' }}** está ahora **{{ $booking->status->label() }}**.
@endif

<x-mail::panel>
### Siguientes pasos
@foreach ($flow['guide']['items'] as $item)
- {{ $item }}
@endforeach
</x-mail::panel>

<x-mail::panel>
### Detalles de la reserva
- Llegada: {{ $booking->arrival->format('d/m/Y') }}
- Salida: {{ $booking->departure->format('d/m/Y') }}
- Huéspedes: {{ $booking->guests }}
- Total estimado: €{{ number_format($booking->total, 2, ',', '.') }}
@if (!empty($booking->notes))
- Comentarios del huésped: {{ $booking->notes }}
@endif
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

@if ($flow['showHouseInfo'])
<x-mail::panel>
### Documentos y horarios
- Check-in: {{ config('villa.check_in_time') }}
- Check-out: {{ config('villa.check_out_time') }}
@if ($flow['houseRulesUrl'])
- [Normas de la casa]({{ $flow['houseRulesUrl'] }})
@endif
@if ($flow['guidebookUrl'])
- [Guía rápida]({{ $flow['guidebookUrl'] }})
@endif
@if ($flow['contractUrl'])
- [Contrato digital]({{ $flow['contractUrl'] }})
@endif
</x-mail::panel>
@endif

@if (! $isOwner && $portalUrl)
<x-mail::button :url="$portalUrl">
Abrir mi portal
</x-mail::button>
@endif

@if ($isOwner && $portalUrl)
<x-mail::button :url="$portalUrl" color="secondary">
Ver portal del huésped
</x-mail::button>
@endif

@if ($isOwner && $adminUrl)
<x-mail::button :url="$adminUrl">
Abrir en el panel
</x-mail::button>
@endif

Gracias,<br>
{{ $flow['ownerName'] }}

@if (! $isOwner)
<p style="font-size: 0.85rem; color: #64748b;">
¿Dudas? Escríbenos a {{ $flow['ownerEmail'] ?? 'nuestro correo habitual' }} o llama al {{ $flow['ownerPhone'] ?? 'teléfono facilitado' }}.
</p>
@endif
</x-mail::message>
