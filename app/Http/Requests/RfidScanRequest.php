<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RfidScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rfid_uid' => ['required', 'string'],
        ];
    }
}
