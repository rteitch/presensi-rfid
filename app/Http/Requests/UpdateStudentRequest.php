<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis'           => ['required', 'string', Rule::unique('students', 'nis')->ignore($this->route('student'))],
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'agama'         => ['nullable', 'string', 'max:50'],
            'rfid_uid'      => ['nullable', 'string', Rule::unique('students', 'rfid_uid')->ignore($this->route('student'))],
            'class_id'      => ['required', 'exists:classes,id'],
            'nama_ortu'     => ['nullable', 'string', 'max:255'],
            'no_hp_ortu'    => ['nullable', 'string', 'max:255'],
            'status'        => ['required', 'in:aktif,nonaktif'],
            'foto'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
