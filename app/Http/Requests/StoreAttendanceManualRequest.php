<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceManualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'   => ['required', 'array', 'min:1'],
            'student_id.*' => ['required', 'integer', 'exists:students,id'],
            'tanggal'      => ['required', 'date'],
            'status'       => ['required', 'in:izin,sakit,alpha,hadir,terlambat'],
            'keterangan'   => ['nullable', 'string'],
        ];
    }
}
