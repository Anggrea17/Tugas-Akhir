<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoHpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // boleh dipakai semua user
    }

  public function rules(): array
{
    return [
        'no_hp' => ['required', 'regex:/^[0-9]{10,20}$/'],
    ];
}

public function messages(): array
{
    return [
        'no_hp.required' => 'Nomor HP wajib diisi.',
        'no_hp.regex' => 'Nomor HP hanya boleh angka dengan panjang 10–20 digit.',
    ];
}

}
