<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // pública, pero podrías meter rate-limit/IP, etc.
        return true;    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'arrival'   => ['required', 'date', 'after_or_equal:today'],
            'departure' => ['required', 'date', 'after:arrival'],
            'guests'    => ['required', 'integer', 'min:1'],

            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],

            'customer_first_name' => ['required', 'string', 'max:255'],
            'customer_first_surname' => ['required', 'string', 'max:255'],
            'customer_second_surname' => ['nullable', 'string', 'max:255'],
            'customer_document_type' => ['required', 'in:dni,nie,passport,other'],
            'customer_document_number' => ['required', 'string', 'max:100'],
            'customer_document_support_number' => ['nullable', 'string', 'max:100'],
            'customer_birthdate' => ['required', 'date', 'before:today'],
            'customer_birth_country' => ['required', 'string', 'max:120'],
            'customer_address_street' => ['required', 'string', 'max:255'],
            'customer_address_number' => ['nullable', 'string', 'max:50'],
            'customer_address_city' => ['required', 'string', 'max:120'],
            'customer_address_province' => ['required', 'string', 'max:120'],
            'customer_address_country' => ['required', 'string', 'max:120'],

            'notes'          => ['nullable', 'string', 'max:2000'],

            // checkbox de términos → boolean + accepted
            'accept_terms'   => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'accept_terms.accepted' => 'Debes aceptar las condiciones de reserva.',
        ];
    }
}
