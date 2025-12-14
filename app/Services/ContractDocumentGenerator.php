<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ContractDocumentGenerator
{
    public function generate(Booking $booking): string
    {
        $booking->loadMissing(['listing', 'guestEntries']);

        $data = [
            'booking' => $booking,
            'listing' => $booking->listing,
            'guests' => $booking->guestEntries,
            'generatedAt' => now(),
        ];

        $html = view('documents.contract', $data)->render();
        $filename = 'contracts/' . $booking->id . '-' . Str::slug($booking->customer_name ?: 'reserva') . '.pdf';

        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
            Storage::disk('public')->put($filename, $pdf->output());
        } else {
            $filename = preg_replace('/\.pdf$/', '.html', $filename);
            Storage::disk('public')->put($filename, $html);
        }

        $booking->forceFill([
            'contract_document_path' => $filename,
        ])->save();

        return $filename;
    }
}
