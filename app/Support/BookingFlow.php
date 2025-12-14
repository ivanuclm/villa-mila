<?php

namespace App\Support;

use App\Enums\BookingStatus;
use App\Models\Booking;

class BookingFlow
{
    public static function context(Booking $booking): array
    {
        $statusEnum = $booking->status instanceof BookingStatus
            ? $booking->status
            : BookingStatus::tryFrom((string) $booking->status);

        $statusValue = $statusEnum?->value ?? (string) $booking->status;

        $ownerName = config('villa.owner_name', 'Milagros');
        $ownerPhone = config('villa.owner_phone');
        $ownerEmail = config('villa.owner_email');

        $guide = match ($statusValue) {
            BookingStatus::Pending->value => [
                'title' => 'Estamos revisando tu solicitud',
                'description' => 'Hemos recibido tu petición y en breve ' . $ownerName . ' confirmará disponibilidad y condiciones.',
                'items' => [
                    'Mantén disponible el teléfono ' . ($booking->customer_phone ?: 'que nos facilitaste') . ' y revisa tu correo por si necesitamos algún dato extra.',
                    'Si deseas modificar fechas o viajeros, responde al correo recibido para que podamos ayudarte.',
                ],
            ],
            BookingStatus::Hold->value => [
                'title' => 'Fechas bloqueadas temporalmente',
                'description' => 'Las fechas están reservadas para ti mientras completamos el pago y los datos necesarios.',
                'items' => [
                    'Realiza la transferencia acordada o confirma con ' . $ownerName . ' si pagarás en mano.',
                    'Envía el comprobante respondiendo al correo o por WhatsApp para acelerar la confirmación.',
                    'Una vez validado el pago se activará el formulario de registro de viajeros.',
                ],
            ],
            BookingStatus::Confirmed->value => [
                'title' => 'Reserva confirmada',
                'description' => 'Todo listo. Ahora necesitamos algunos datos para cumplir con el registro oficial de viajeros.',
                'items' => [
                    'Rellena el formulario por cada huésped y firma digitalmente.',
                    'Revisa las normas de la casa y el contrato digital disponibles más abajo.',
                    'Unos días antes del check-in recibirás recordatorios con horarios y acceso.',
                ],
            ],
            BookingStatus::InStay->value => [
                'title' => 'Estancia en curso',
                'description' => 'Esperamos que estéis disfrutando de Villa Mila.',
                'items' => [
                    'Ante cualquier incidencia contacta al instante con ' . ($ownerPhone ?: $ownerEmail),
                    'Recuerda la hora de salida ' . config('villa.check_out_time', 'indicada en la reserva') . '.',
                    'Puedes consultar las normas o datos relevantes en esta misma página.',
                ],
            ],
            BookingStatus::Completed->value => [
                'title' => 'Reserva completa',
                'description' => 'Gracias por confiar en nosotros. Aquí mantendrás un histórico de todo lo que compartiste.',
                'items' => [
                    'Puedes guardar este enlace por si necesitas descargar datos para futuras gestiones.',
                    'Si todavía no lo has hecho, nos encantará recibir tu opinión o reseña.',
                ],
            ],
            BookingStatus::Cancelled->value => [
                'title' => 'Reserva cancelada',
                'description' => 'Hemos registrado tu cancelación. Si deseas reprogramar, contacta con nosotros.',
                'items' => [
                    'Guarda esta página como comprobante del estado actual.',
                    'Para nuevas fechas o dudas escribe a ' . ($ownerEmail ?? 'nuestro correo de contacto') . '.',
                ],
            ],
            default => [
                'title' => 'Actualización en curso',
                'description' => 'En breve te confirmaremos los siguientes pasos.',
                'items' => [
                    'Si tienes dudas, habla con ' . $ownerName . '.',
                ],
            ],
        };

        return [
            'statusValue' => $statusValue,
            'guide' => $guide,
            'ownerName' => $ownerName,
            'ownerPhone' => $ownerPhone,
            'ownerEmail' => $ownerEmail,
            'payment' => config('villa.payment', []),
            'houseRulesUrl' => config('villa.house_rules_url'),
            'guidebookUrl' => config('villa.guidebook_url'),
            'contractUrl' => config('villa.contract_url'),
            'showPaymentInstructions' => in_array($statusValue, [
                BookingStatus::Hold->value,
                BookingStatus::Confirmed->value,
            ], true),
            'showHouseInfo' => in_array($statusValue, [
                BookingStatus::Confirmed->value,
                BookingStatus::InStay->value,
                BookingStatus::Completed->value,
            ], true),
            'canSeeTravelerSection' => in_array($statusValue, [
                BookingStatus::Confirmed->value,
                BookingStatus::InStay->value,
                BookingStatus::Completed->value,
            ], true),
            'canManageGuests' => in_array($statusValue, [
                BookingStatus::Confirmed->value,
                BookingStatus::InStay->value,
            ], true),
            'canAddGuests' => in_array($statusValue, [
                BookingStatus::Confirmed->value,
                BookingStatus::InStay->value,
            ], true),
        ];
    }
}
