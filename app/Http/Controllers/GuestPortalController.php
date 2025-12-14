<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuestPortalController extends Controller
{
    public function show(string $token)
    {
        $booking = Booking::with(['listing', 'guestEntries'])
            ->where('public_access_token', $token)
            ->firstOrFail();

        $booking->updateQuietly(['portal_last_accessed_at' => now()]);

        return view('portal.booking', [
            'booking' => $booking,
        ]);
    }

    public function storeGuest(Request $request, string $token)
    {
        $booking = Booking::where('public_access_token', $token)->firstOrFail();

        if ($booking->guestEntries()->count() >= $booking->guests) {
            return back()->with('portal_error', 'Ya has registrado el número máximo de viajeros incluidos en tu reserva.');
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'first_surname' => ['required', 'string', 'max:255'],
            'second_surname' => ['nullable', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'document_type' => ['nullable', Rule::in(['dni', 'nie', 'passport', 'other'])],
            'document_number' => ['nullable', 'string', 'max:100'],
            'document_support_number' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['M', 'H', 'X'])],
            'nationality' => ['nullable', 'string', 'max:100'],
            'birth_country' => ['nullable', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date'],
            'is_minor' => ['nullable', 'boolean'],
            'kinship' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'signature_data' => ['nullable', 'string'],
        ]);

        $signaturePath = null;
        if (! empty($data['signature_data'])) {
            $signaturePath = $this->storeSignature($data['signature_data']);
        }

        $booking->guestEntries()->create([
            'first_name' => $data['first_name'],
            'first_surname' => $data['first_surname'],
            'second_surname' => $data['second_surname'] ?? null,
            'full_name' => $data['full_name'] ?? $this->composeFullName($data),
            'document_type' => $data['document_type'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'document_support_number' => $data['document_support_number'] ?? null,
            'gender' => $data['gender'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'birth_country' => $data['birth_country'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'is_minor' => (bool) ($data['is_minor'] ?? false),
            'kinship' => $data['kinship'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'signature_path' => $signaturePath,
        ]);

        return back()->with('portal_status', 'Hemos guardado los datos de la persona viajera.');
    }

    public function updateGuest(Request $request, string $token, BookingGuest $guest)
    {
        $booking = Booking::where('public_access_token', $token)->firstOrFail();

        abort_unless($guest->booking_id === $booking->id, 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'first_surname' => ['required', 'string', 'max:255'],
            'second_surname' => ['nullable', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'document_type' => ['nullable', Rule::in(['dni', 'nie', 'passport', 'other'])],
            'document_number' => ['nullable', 'string', 'max:100'],
            'document_support_number' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['M', 'H', 'X'])],
            'nationality' => ['nullable', 'string', 'max:100'],
            'birth_country' => ['nullable', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date'],
            'is_minor' => ['nullable', 'boolean'],
            'kinship' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $guest->update([
            'first_name' => $data['first_name'],
            'first_surname' => $data['first_surname'],
            'second_surname' => $data['second_surname'] ?? null,
            'full_name' => $data['full_name'] ?? $this->composeFullName($data),
            'document_type' => $data['document_type'] ?? null,
            'document_number' => $data['document_number'] ?? null,
            'document_support_number' => $data['document_support_number'] ?? null,
            'gender' => $data['gender'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'birth_country' => $data['birth_country'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'is_minor' => (bool) ($data['is_minor'] ?? false),
            'kinship' => $data['kinship'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        return back()->with('portal_status', 'Datos del viajero actualizados.');
    }

    public function destroyGuest(string $token, BookingGuest $guest)
    {
        $booking = Booking::where('public_access_token', $token)->firstOrFail();

        abort_unless($guest->booking_id === $booking->id, 404);

        if ($guest->signature_path) {
            Storage::disk('public')->delete($guest->signature_path);
        }

        $guest->delete();

        return back()->with('portal_status', 'Viajero eliminado de la reserva.');
    }

    protected function storeSignature(string $data): ?string
    {
        if (! str_contains($data, 'base64,')) {
            return null;
        }

        [$meta, $content] = explode('base64,', $data);
        $binary = base64_decode($content);
        if ($binary === false) {
            return null;
        }

        $extension = str_contains($meta, 'image/png') ? 'png' : 'jpg';
        $path = 'guest-signatures/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    protected function composeFullName(array $data): ?string
    {
        $parts = array_filter([
            $data['first_name'] ?? null,
            $data['first_surname'] ?? null,
            $data['second_surname'] ?? null,
        ]);

        return $parts ? implode(' ', $parts) : null;
    }
}
