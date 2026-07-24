<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:255'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ];
    }
}
