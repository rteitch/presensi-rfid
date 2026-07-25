<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis'           => ['required', 'string', 'unique:students,nis'],
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'agama'         => ['nullable', 'string', 'max:50'],
            'rfid_uid'      => ['nullable', 'string', 'unique:students,rfid_uid'],
            'class_id'      => ['required', 'exists:classes,id'],
            'nama_ortu'     => ['nullable', 'string', 'max:255'],
            'no_hp_ortu'    => ['nullable', 'string', 'max:255'],
            'status'        => ['required', 'in:aktif,nonaktif'],
            'foto'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
