<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'required|string',
            'phones'   => 'required|array|size:1',
            'phones.*' => 'min:10|regex:/\d+/',

            'emails'   => 'array|size:1',
            'emails.*' => 'string|unique:emails,email',
            // 'image' => 'string',

            'places'             => 'array',
            'places.*.name'      => 'required|string',
            'places.*.latitude'  => 'required|numeric',
            'places.*.longitude' => 'required|numeric',
        ];
    }
}
