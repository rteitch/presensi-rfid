<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_device' => ['required', 'string', 'max:255'],
            'tipe_device' => ['required', 'in:kiosk,microcontroller'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'token_device' => ['required', 'string', 'unique:devices,token_device'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
