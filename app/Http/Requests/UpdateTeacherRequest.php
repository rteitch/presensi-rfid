<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', 'max:255', Rule::unique('teachers', 'nip')->ignore($this->route('teacher'))],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($this->route('teacher'))],
            'user_id' => ['nullable', 'exists:users,id'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'mata_pelajaran' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ];
    }
}
