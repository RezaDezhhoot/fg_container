<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendCartRequest extends FormRequest
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
            'phone' => 'required|exists:panels,phone',
            'base_id' => 'required|integer',
            'panel_id' => ['required','exists:panels,id']
        ];
    }

    public function attributes(): array
    {
        return [
            'phone' => 'شماره همراه',
            'username' => 'نام کاربری',
            'code' => 'کد احراز هویت',
            'category_id' => 'دسته بندی',
            'base_id' => 'کد سفارش'
        ];
    }
}
