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
            'username' => 'required|string|exists:panels,username',
            'phone' => 'required|exists:panels,phone',
            'code' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_id' => 'required|integer'
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
