<x-mail::message>
# {{ $isOwner ? 'Actualización de reserva' : 'Hemos actualizado tu reserva' }}

@if($isOwner)
Se ha modificado el estado de la reserva de **{{ $booking->customer_name }}**.
@else
Tu estancia en **{{ $booking->listing->name ?? 'Villa Mila' }}** ahora está en estado **{{ $booking->status->label() }}**.
@endif

@if($previousStatus)
Estado anterior: **{{ $previousStatus }}**
@endif

---

## Detalles

- **Llegada:** {{ $booking->arrival->format('d/m/Y') }}
- **Salida:** {{ $booking->departure->format('d/m/Y') }}
- **Huéspedes:** {{ $booking->guests }}
- **Total estimado:** €{{ number_format($booking->total, 2, ',', '.') }}

@if(!empty($booking->notes))
- **Notas del huésped:** {{ $booking->notes }}
@endif

---

@if($booking->status->value === 'hold')
- Estamos reteniendo tus fechas temporalmente. Te enviaremos los pasos para confirmar o realizar el pago.
@elseif($booking->status->value === 'confirmed')
- Todo listo. Te compartiremos instrucciones finales y accesos unos días antes de tu llegada.
@elseif($booking->status->value === 'in_stay')
- ¡Bienvenidos! Disfrutad de la casa. Recuerda contactar a Milagros para cualquier ayuda.
@elseif($booking->status->value === 'completed')
- Gracias por confiar en nosotros. Nos encantará leer tu reseña cuando tengas un momento.
@elseif($booking->status->value === 'cancelled')
- La reserva ha quedado cancelada. Si necesitas nuevas fechas estaremos encantados de ayudarte.
@endif

@if($isOwner && $booking->public_access_token)
<x-mail::button :url="route('guest.portal.show', $booking->public_access_token)">
Ver portal del huésped
</x-mail::button>
@endif

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
