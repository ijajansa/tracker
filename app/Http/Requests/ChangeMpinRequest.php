<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeMpinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_mpin' => ['required', 'digits:4'],
            'mpin' => ['required', 'digits:4', 'confirmed'],
        ];
    }
}
