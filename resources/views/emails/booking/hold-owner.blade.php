<x-mail::message>
# Nueva solicitud de reserva (HOLD)

Has recibido una nueva solicitud de reserva para **{{ $booking->listing->name ?? 'Villa Mila' }}**.

---

## Datos del huésped

- **Nombre:** {{ $booking->customer_name }}
- **Email:** {{ $booking->customer_email }}
@if(!empty($booking->customer_phone))
- **Teléfono:** {{ $booking->customer_phone }}
@endif

@if(!empty($booking->notes))
- **Comentarios:** {{ $booking->notes }}
@endif

---

## Detalles de la reserva

- **Llegada:** {{ $booking->arrival->format('Y-m-d') }}
- **Salida:** {{ $booking->departure->format('Y-m-d') }}
- **Huéspedes:** {{ $booking->guests }}
- **Estado:** HOLD

---

## Resumen

- **Noches:** {{ $quote['nights'] }}
- **Subtotal:** €{{ number_format($quote['subtotal'], 2, ',', '.') }}
- **Limpieza:** €{{ number_format($quote['cleaning'], 2, ',', '.') }}
- **Total:** €{{ number_format($quote['total'], 2, ',', '.') }}

<x-mail::button :url="$adminUrl">
Ver reserva en el panel
</x-mail::button>

</x-mail::message>
