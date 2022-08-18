<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SendLicenseRequest extends FormRequest
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

    #[ArrayShape(['phone' => "string", 'code' => "string", 'product_title' => "string", 'count' => "string", 'exit_price' => "string", 'base_id' => "string"])]
    public function rules(): array
    {
        return [
            'phone' => 'required|string|size:11',
            'code' => 'required|string',
            'product_title' => 'required|string|max:250',
            'count' => 'required|integer|between:1,100',
            'exit_price' =>'required|between:1,999999999999.9999999',
            'base_id' => 'required|integer'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    #[ArrayShape(['phone' => "string", 'code' => "string", 'count' => "string", 'exit_price' => "string", 'product_title' => "string", 'base_id' => "string"])]
    public function attributes(): array
    {
        return [
            'phone' => 'شماره همراه',
            'code' => 'کد احراز هویت',
            'count' => 'تعداد',
            'exit_price' => 'قیمت فروش',
            'product_title' => 'عنوان محصول',
            'base_id' => 'کد سفارش'
        ];
    }
}
