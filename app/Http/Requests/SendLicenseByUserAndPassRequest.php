<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendLicenseByUserAndPassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'admin_phone' => 'required|string|size:11|exists:users,phone',
            'admin_code' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'admin_phone' => 'شماره همراه',
            'admin_code' => 'کد احراز هویت',
        ];
    }
}
