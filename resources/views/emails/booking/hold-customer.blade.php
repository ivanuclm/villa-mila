<x-mail::message>
# Hemos recibido tu solicitud de reserva

Hola {{ $booking->customer_name }},

Hemos recibido tu solicitud para **{{ $booking->listing->name ?? 'Villa Mila' }}**.

Tu reserva está en estado **PENDIENTE**, lo que significa que revisaremos la información y te escribiremos con los siguientes pasos.

---

## Detalles de la reserva

- **Llegada:** {{ $booking->arrival->format('Y-m-d') }}
- **Salida:** {{ $booking->departure->format('Y-m-d') }}
- **Huéspedes:** {{ $booking->guests }}

@if(!empty($booking->customer_phone))
- **Teléfono:** {{ $booking->customer_phone }}
@endif

@if(!empty($booking->notes))
- **Comentarios:** {{ $booking->notes }}
@endif

---

## Resumen

- **Noches:** {{ $quote['nights'] }}
- **Subtotal:** €{{ number_format($quote['subtotal'], 2, ',', '.') }}
- **Limpieza:** €{{ number_format($quote['cleaning'], 2, ',', '.') }}
- **Total:** €{{ number_format($quote['total'], 2, ',', '.') }}

---

En breve nos pondremos en contacto para confirmarla o solicitar información adicional.

Gracias,  
{{ config('app.name') }}
</x-mail::message>
