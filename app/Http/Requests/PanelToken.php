<?php

namespace App\Http\Requests;

use App\Enums\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;

class PanelToken extends FormRequest
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
        $rules = [
            'token' => ['required','exists:panels,token'],
            'count' => ['nullable','integer','between:1,10000000'],
            'type' => ['nullable','in:'.implode(',',array_keys(CategoryEnum::getType()))],
            'base' => ['nullable']
        ];
//        if (request()->getMethod() == 'PUT') {
//            return  [];
//        }
        return $rules;
    }
}
